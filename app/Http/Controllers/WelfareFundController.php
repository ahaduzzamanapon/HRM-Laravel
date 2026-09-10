<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WelfareFundContribution;
use App\Models\MedicalSupport;
use App\Models\FuneralSupport;
use App\Models\EmployeeChildrenEducationSupport;
use App\Models\WelfareFundSetting;
use App\Services\WelfareFundService;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;

class WelfareFundController extends Controller
{
    public function dashboard(Request $request)
    {
        if (AuthorizationEngine::isEmployeeRole(Auth::user()) && !AuthorizationEngine::isSuperAdmin(Auth::user())) {
            return redirect()->route('welfare.myStatement');
        }

        $summary = WelfareFundService::getFundSummary();

        $recentContributions = WelfareFundContribution::with('user:id,name,last_name,emp_id')
            ->latest()
            ->take(8)
            ->get();

        $recentMedical = MedicalSupport::with('user:id,name,last_name,emp_id')->latest()->take(5)->get()->map(function($item) {
            $item->type = 'Medical Support';
            $item->display_amount = $item->approved_amount ?? $item->amount;
            return $item;
        });

        $recentFuneral = FuneralSupport::with('user:id,name,last_name,emp_id')->latest()->take(5)->get()->map(function($item) {
            $item->type = 'Funeral Support';
            $item->display_amount = $item->approved_amount ?? $item->amount;
            return $item;
        });

        $recentEducation = EmployeeChildrenEducationSupport::with('user:id,name,last_name,emp_id')->latest()->take(5)->get()->map(function($item) {
            $item->type = 'Children Education Support';
            $item->display_amount = $item->approved_amount ?? $item->financial_assistance;
            return $item;
        });

        $recentApplications = $recentMedical->concat($recentFuneral)->concat($recentEducation)
            ->sortByDesc('created_at')
            ->take(8);

        return view('welfare_fund.dashboard', array_merge($summary, [
            'recentContributions' => $recentContributions,
            'recentApplications' => $recentApplications,
        ]));
    }

    public function myStatement()
    {
        $userId = Auth::id();

        $contributions = WelfareFundContribution::where('user_id', $userId)
            ->latest('contribution_date')
            ->get();

        $totalContributed = $contributions->sum('amount');

        $medicalSupports = MedicalSupport::where('employee_id', $userId)->latest()->get()->map(function($item) {
            $item->type = 'Medical Support';
            $item->display_amount = $item->disbursed_amount ?? ($item->approved_amount ?? $item->amount);
            return $item;
        });

        $funeralSupports = FuneralSupport::where('employee_id', $userId)->latest()->get()->map(function($item) {
            $item->type = 'Funeral Support';
            $item->display_amount = $item->disbursed_amount ?? ($item->approved_amount ?? $item->amount);
            return $item;
        });

        $educationSupports = EmployeeChildrenEducationSupport::where('employee_id', $userId)->latest()->get()->map(function($item) {
            $item->type = 'Children Education Support';
            $item->display_amount = $item->disbursed_amount ?? ($item->approved_amount ?? $item->financial_assistance);
            return $item;
        });

        $supports = $medicalSupports->concat($funeralSupports)->concat($educationSupports)
            ->sortByDesc('support_date');

        $totalBenefitReceived = $supports->whereIn('status', ['Approved', 'Disbursed'])->sum('display_amount');

        return view('welfare_fund.statement', compact(
            'contributions',
            'totalContributed',
            'supports',
            'totalBenefitReceived'
        ));
    }

    public function ledger(Request $request)
    {
        if (AuthorizationEngine::isEmployeeRole(Auth::user()) && !AuthorizationEngine::isSuperAdmin(Auth::user())) {
            Flash::error('Access restricted to HR and Administration.');
            return redirect()->route('welfare.myStatement');
        }

        $query = WelfareFundContribution::with('user:id,name,last_name,emp_id')
            ->orderBy('contribution_date', 'asc')
            ->orderBy('id', 'asc');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $contributions = $query->paginate(20);
        $summary = WelfareFundService::getFundSummary();

        return view('welfare_fund.ledger', compact('contributions', 'summary'));
    }

    public function reports(Request $request)
    {
        if (AuthorizationEngine::isEmployeeRole(Auth::user()) && !AuthorizationEngine::isSuperAdmin(Auth::user())) {
            Flash::error('Access restricted to HR and Administration.');
            return redirect()->route('welfare.myStatement');
        }

        $month = $request->input('month');
        $year = $request->input('year', date('Y'));
        $type = $request->input('type', 'all');
        $export = $request->input('export');

        $monthNum = null;
        if (!empty($month)) {
            $parsedTimestamp = strtotime($month);
            if ($parsedTimestamp !== false) {
                $monthNum = (int) date('m', $parsedTimestamp);
            } elseif (is_numeric($month)) {
                $monthNum = (int) $month;
            }
        }

        $contributions = WelfareFundContribution::with('user:id,name,last_name,emp_id')
            ->when($year, fn($q) => $q->where('year', $year))
            ->when($month, fn($q) => $q->where('month', $month))
            ->latest('contribution_date')
            ->get();

        $medical = MedicalSupport::with('employee:id,name,last_name,emp_id')
            ->when($year, fn($q) => $q->whereYear('support_date', $year))
            ->when($monthNum, fn($q) => $q->whereMonth('support_date', $monthNum))
            ->get()->map(function($i){ $i->support_category = 'Medical'; return $i; });

        $funeral = FuneralSupport::with('employee:id,name,last_name,emp_id')
            ->when($year, fn($q) => $q->whereYear('support_date', $year))
            ->when($monthNum, fn($q) => $q->whereMonth('support_date', $monthNum))
            ->get()->map(function($i){ $i->support_category = 'Funeral'; return $i; });

        $education = EmployeeChildrenEducationSupport::with('employee:id,name,last_name,emp_id')
            ->when($year, fn($q) => $q->whereYear('support_date', $year))
            ->when($monthNum, fn($q) => $q->whereMonth('support_date', $monthNum))
            ->get()->map(function($i){ $i->support_category = 'Education'; return $i; });

        $disbursements = $medical->concat($funeral)->concat($education)
            ->whereIn('status', ['Approved', 'Disbursed'])
            ->sortByDesc('support_date');

        if ($export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('welfare_fund.reports_pdf', compact('contributions', 'disbursements', 'year', 'month', 'type'));
            return $pdf->setPaper('a4', 'portrait')->download('welfare_fund_report_' . $year . '_' . ($month ?: 'all') . '.pdf');
        }

        if ($export === 'excel' || $export === 'csv') {
            try {
                if (class_exists('\Maatwebsite\Excel\Facades\Excel') && class_exists('\App\Exports\GenericReportExport')) {
                    return \Maatwebsite\Excel\Facades\Excel::download(
                        new \App\Exports\GenericReportExport('welfare_fund.reports_pdf', compact('contributions', 'disbursements', 'year', 'month', 'type')),
                        'welfare_fund_report_' . $year . '_' . ($month ?: 'all') . '.xlsx'
                    );
                }
            } catch (\Throwable $e) {
                // Fallback to CSV stream
            }
            return $this->exportCsv($contributions, $disbursements, $year, $month);
        }

        return view('welfare_fund.reports', compact('contributions', 'disbursements', 'year', 'month', 'type'));
    }

    protected function exportCsv($contributions, $disbursements, $year, $month)
    {
        $fileName = 'welfare_fund_report_' . $year . '_' . ($month ?: 'all') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($contributions, $disbursements) {
            $file = fopen('php://output', 'w');

            // Contributions
            fputcsv($file, ['CONTRIBUTION REPORT']);
            fputcsv($file, ['#', 'Employee Name', 'Employee ID', 'Contribution Type', 'Period', 'Date', 'Amount (BDT)']);
            foreach ($contributions as $index => $c) {
                fputcsv($file, [
                    $index + 1,
                    trim(($c->user->name ?? '') . ' ' . ($c->user->last_name ?? '')),
                    $c->user->emp_id ?? 'N/A',
                    ucfirst($c->contribution_type ?? 'Employee'),
                    $c->month . ' ' . $c->year,
                    \Carbon\Carbon::parse($c->contribution_date)->format('Y-m-d'),
                    $c->amount
                ]);
            }
            fputcsv($file, []);

            // Disbursements
            fputcsv($file, ['DISBURSEMENT REPORT']);
            fputcsv($file, ['#', 'Employee Name', 'Employee ID', 'Support Category', 'Date', 'Approved Amount', 'Disbursed Amount', 'Status']);
            foreach ($disbursements as $index => $d) {
                fputcsv($file, [
                    $index + 1,
                    trim(($d->employee->name ?? '') . ' ' . ($d->employee->last_name ?? '')),
                    $d->employee->emp_id ?? 'N/A',
                    $d->support_category,
                    \Carbon\Carbon::parse($d->support_date)->format('Y-m-d'),
                    $d->approved_amount ?? $d->amount ?? $d->financial_assistance,
                    $d->disbursed_amount ?? $d->approved_amount ?? $d->amount ?? $d->financial_assistance,
                    $d->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function approve(Request $request, $type, $id)
    {
        $request->validate([
            'approved_amount' => 'required|numeric|min:0.01',
            'admin_remarks' => 'nullable|string',
        ]);

        $model = match($type) {
            'medical' => MedicalSupport::findOrFail($id),
            'funeral' => FuneralSupport::findOrFail($id),
            'education' => EmployeeChildrenEducationSupport::findOrFail($id),
            default => abort(404),
        };

        if ($model->status === 'Disbursed') {
            Flash::error('Disbursed applications cannot be modified.');
            return redirect()->back();
        }

        $oldStatus = $model->status;
        $model->status = 'Approved';
        $model->approved_amount = $request->approved_amount;
        $model->approved_by = Auth::id();
        $model->approved_at = now();
        $model->admin_remarks = $request->admin_remarks;
        $model->save();

        WelfareFundService::audit('approve', $type, $id, $oldStatus, 'Approved', "Approved amount: {$request->approved_amount}");

        Flash::success('Support application approved successfully.');
        return redirect()->back();
    }

    public function reject(Request $request, $type, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:3',
        ]);

        $model = match($type) {
            'medical' => MedicalSupport::findOrFail($id),
            'funeral' => FuneralSupport::findOrFail($id),
            'education' => EmployeeChildrenEducationSupport::findOrFail($id),
            default => abort(404),
        };

        if ($model->status === 'Disbursed') {
            Flash::error('Disbursed applications cannot be rejected.');
            return redirect()->back();
        }

        $oldStatus = $model->status;
        $model->status = 'Rejected';
        $model->rejection_reason = $request->rejection_reason;
        $model->approved_by = Auth::id();
        $model->approved_at = now();
        $model->save();

        WelfareFundService::audit('reject', $type, $id, $oldStatus, 'Rejected', "Rejection reason: {$request->rejection_reason}");

        Flash::success('Support application rejected successfully.');
        return redirect()->back();
    }

    public function disburse(Request $request, $type, $id)
    {
        $request->validate([
            'disbursed_amount' => 'required|numeric|min:0.01',
            'disbursement_reference' => 'required|string',
            'admin_remarks' => 'nullable|string',
        ]);

        $settings = WelfareFundSetting::instance();
        $summary = WelfareFundService::getFundSummary();

        if (!$settings->allow_negative_balance && ($summary['currentBalance'] - $request->disbursed_amount) < 0) {
            Flash::error("Disbursement blocked: Current fund balance (৳" . number_format($summary['currentBalance'], 2) . ") is insufficient.");
            return redirect()->back();
        }

        $model = match($type) {
            'medical' => MedicalSupport::findOrFail($id),
            'funeral' => FuneralSupport::findOrFail($id),
            'education' => EmployeeChildrenEducationSupport::findOrFail($id),
            default => abort(404),
        };

        if ($model->status === 'Disbursed') {
            Flash::error('This application has already been disbursed.');
            return redirect()->back();
        }

        DB::transaction(function() use ($model, $request, $type, $id) {
            $oldStatus = $model->status;
            $model->status = 'Disbursed';
            $model->disbursed_amount = $request->disbursed_amount;
            $model->disbursed_by = Auth::id();
            $model->disbursed_at = now();
            $model->disbursement_reference = $request->disbursement_reference;
            if ($request->filled('admin_remarks')) {
                $model->admin_remarks = $request->admin_remarks;
            }
            $model->save();

            WelfareFundService::audit('disburse', $type, $id, $oldStatus, 'Disbursed', "Disbursed amount: ৳{$request->disbursed_amount}, Ref: {$request->disbursement_reference}");
        });

        Flash::success('Support application disbursed successfully.');
        return redirect()->back();
    }
}
