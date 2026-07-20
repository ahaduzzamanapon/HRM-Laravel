<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PensionDisbursement;

class DisbursementController extends Controller
{
    public function index()
    {
        $disbursements = PensionDisbursement::with(['profile.user', 'profile.calculation'])->latest()->paginate(10);
        return view('admin.pension.disbursements.index', compact('disbursements'));
    }

    public function process()
    {
        // Fetch all calculations that are finalized or approved and don't have a disbursement for the current month
        $currentMonth = \Carbon\Carbon::now()->format('F Y');

        $calculations = \App\Models\PensionCalculation::whereIn('status', ['Approved', 'Finalized', 'Draft'])
            ->whereDoesntHave('profile.disbursements', function ($query) use ($currentMonth) {
                $query->where('disbursement_month', $currentMonth);
            })
            ->get();

        $count = 0;
        foreach ($calculations as $calc) {
            $profile = $calc->profile;
            if (!$profile) {
                continue;
            }
            $scheme = $profile->scheme;
            $policy = $scheme->policy ?? null;
            if (!$policy || $policy->status !== 'Active') {
                $policy = \App\Models\PensionPolicy::where('status', 'Active')->first();
            }

            // 1. Monthly Pension
            $monthlyPension = $calc->monthly_pension ?? 0.00;

            // 2. Allowances (Medical Allowance)
            $medicalAllowance = $calc->medical_allowance ?? 0.00;

            // 3. Tax deduction
            $taxDeduction = 0.00;
            if ($policy && $policy->taxRule && $policy->taxRule->taxable) {
                $taxRule = $policy->taxRule;
                $baseSalary = $profile->last_basic_pay ?? 0.00;
                if ($taxRule->tax_method === 'Percentage' && $taxRule->tax_percentage > 0) {
                    $taxDeduction = $baseSalary * ($taxRule->tax_percentage / 100);
                } elseif ($taxRule->tax_method === 'Fixed') {
                    $taxDeduction = $taxRule->tax_percentage;
                }
            }

            // 4. Arrear payment (derived dynamically from effective revision rules)
            $arrearAmount = 0.00;
            if ($policy) {
                $activeRevisions = $policy->revisionRules()
                    ->where('status', 'Active')
                    ->where('effective_date', '<=', now()->format('Y-m-d'))
                    ->get();

                foreach ($activeRevisions as $revision) {
                    $effectiveDate = \Carbon\Carbon::parse($revision->effective_date);
                    $now = \Carbon\Carbon::now();
                    $monthsDiff = $effectiveDate->diffInMonths($now);
                    if ($monthsDiff > 0) {
                        $arrearAmount += ($monthlyPension * ($revision->revision_percentage / 100)) * $monthsDiff;
                    }
                }
            }

            // 5. Net Payable
            $netPayable = $monthlyPension + $medicalAllowance - $taxDeduction + $arrearAmount;
            if ($netPayable < 0) {
                $netPayable = 0.00;
            }

            // 6. Payment method
            $paymentMethod = 'EFT';
            if ($policy && $policy->paymentRule && $policy->paymentRule->status === 'Active') {
                $paymentMethod = $policy->paymentRule->payment_method ?? 'EFT';
            } else {
                $paymentMethod = ($profile->user->pay_type ?? 'cash') === 'cash' ? 'Cheque' : 'EFT';
            }

            PensionDisbursement::create([
                'profile_id' => $calc->profile_id,
                'disbursement_month' => $currentMonth,
                'amount' => $monthlyPension + $medicalAllowance,
                'arrear_amount' => $arrearAmount,
                'deductions' => $taxDeduction,
                'net_payable' => $netPayable,
                'payment_method' => $paymentMethod,
                'status' => 'Processing',
            ]);
            $count++;
        }

        return redirect()->back()->with('success', "Processed disbursements for {$count} approved pension profiles for {$currentMonth}.");
    }

    public function pay(Request $request, $id)
    {
        $disbursement = PensionDisbursement::findOrFail($id);
        
        $paidAt = $request->input('paid_at') ? \Carbon\Carbon::parse($request->input('paid_at')) : now();
        
        $disbursement->update([
            'status' => 'Paid',
            'bank_reference' => $request->input('bank_reference') ?: 'REF' . rand(100000, 999999),
            'paid_at' => $paidAt,
        ]);

        return redirect()->back()->with('success', 'Pension disbursement marked as Paid successfully.');
    }

    public function downloadPayslip($id)
    {
        $disbursement = PensionDisbursement::with(['profile.user', 'profile.scheme.policy', 'profile.calculation'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pension.disbursements.payslip_pdf', compact('disbursement'))
            ->setPaper('a5', 'portrait');
        return $pdf->download("pension_payslip_{$disbursement->profile->user->name}_{$disbursement->disbursement_month}.pdf");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $disbursement = PensionDisbursement::findOrFail($id);
        
        $request->validate([
            'payment_method' => 'required|string',
            'status' => 'required|string|in:Pending,Processing,Paid,Failed',
            'bank_reference' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $status = $request->input('status');
        $paidAt = null;

        if ($status === 'Paid') {
            $paidAt = $request->input('paid_at') ? \Carbon\Carbon::parse($request->input('paid_at')) : ($disbursement->paid_at ?? now());
        }

        $disbursement->update([
            'payment_method' => $request->input('payment_method'),
            'status' => $status,
            'bank_reference' => $status === 'Paid' ? ($request->input('bank_reference') ?: ($disbursement->bank_reference ?: 'REF' . rand(100000, 999999))) : null,
            'paid_at' => $paidAt,
        ]);

        return redirect()->back()->with('success', 'Pension disbursement updated successfully.');
    }

    public function destroy($id)
    {
        //
    }
}
