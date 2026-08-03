<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProvidentFundContribution;
use App\Models\PfLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PfContributionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $contributionsQuery = ProvidentFundContribution::with(['employee', 'branch', 'scheme'])
                            ->orderBy('contribution_date', 'desc');
                            
        $employeesQuery = \App\Models\User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $contributionsQuery->whereHas('employee', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
            $employeesQuery->where('branch_id', $user->branch_id);
        }

        $contributions = $contributionsQuery->paginate(20);
        $employees = $employeesQuery->get();
        $schemes = \App\Models\PfScheme::all();
        
        return view('pf.contributions.index', compact('contributions', 'employees', 'schemes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'scheme_id' => 'required|exists:pf_schemes,id',
            'contribution_date' => 'required|date',
            'employee_contribution' => 'required|numeric|min:0',
            'employer_contribution' => 'required|numeric|min:0',
        ]);

        $employee = \App\Models\User::findOrFail($request->employee_id);

        if ($employee->is_pf_member != 1) {
            return redirect()->back()->with('error', 'Selected employee is not registered as a Provident Fund member.');
        }

        ProvidentFundContribution::create([
            'employee_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'scheme_id' => $request->scheme_id,
            'contribution_date' => $request->contribution_date,
            'employee_contribution' => $request->employee_contribution,
            'employer_contribution' => $request->employer_contribution,
            'voluntary_contribution' => $request->voluntary_contribution ?? 0,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'PF Contribution recorded successfully.');
    }

    public function process(Request $request, ProvidentFundContribution $contribution)
    {
        if ($contribution->status === 'Processed') {
            return redirect()->back()->with('error', 'Contribution is already processed.');
        }

        DB::beginTransaction();
        try {
            if (!$contribution->employee || $contribution->employee->is_pf_member != 1) {
                return redirect()->back()->with('error', 'The employee is no longer an active Provident Fund member. Contribution aborted.');
            }

            $contribution->status = 'Processed';
            $contribution->save();

            // Create Immutable Ledger Entry
            PfLedger::create([
                'employee_id' => $contribution->employee_id,
                'branch_id' => $contribution->branch_id,
                'scheme_id' => $contribution->scheme_id,
                'transaction_type' => 'contribution',
                'credit' => $contribution->employee_contribution + $contribution->employer_contribution + $contribution->voluntary_contribution,
                'debit' => 0,
                'balance' => $this->getCurrentBalance($contribution->employee_id) + $contribution->employee_contribution + $contribution->employer_contribution + $contribution->voluntary_contribution,
                'description' => 'Monthly PF Contribution',
                'reference_type' => get_class($contribution),
                'reference_id' => $contribution->id,
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->route('pf.contributions.index')->with('success', 'Contribution processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process contribution: ' . $e->getMessage());
        }
    }

    private function getCurrentBalance($employeeId)
    {
        $lastLedger = PfLedger::where('employee_id', $employeeId)->orderBy('id', 'desc')->first();
        return $lastLedger ? $lastLedger->balance : 0;
    }
}
