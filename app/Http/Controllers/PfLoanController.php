<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProvidentFundLoan;
use App\Models\PfApprovalWorkflow;
use App\Models\PfLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PfLoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $loansQuery = ProvidentFundLoan::with(['employee'])->orderBy('created_at', 'desc');
        $employeesQuery = \App\Models\User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $loansQuery->whereHas('employee', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
            $employeesQuery->where('branch_id', $user->branch_id);
        }

        $loans = $loansQuery->paginate(20);
        $employees = $employeesQuery->get();

        return view('pf.loans.index', compact('loans', 'employees'));
    }

    public function create()
    {
        return view('pf.loans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1000',
            'installments' => 'required|integer|min:1',
            'remarks' => 'nullable|string'
        ]);

        $employee = \App\Models\User::findOrFail($request->employee_id);

        if ($employee->is_pf_member != 1) {
            return redirect()->back()->with('error', 'Selected employee is not registered as a Provident Fund member.');
        }

        $loan = ProvidentFundLoan::create([
            'employee_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'amount' => $request->amount,
            'installments' => $request->installments,
            'monthly_installment' => $request->amount / $request->installments,
            'outstanding_balance' => $request->amount,
            'status' => 'Pending',
            'workflow_status' => 'Pending HR Approval',
            'remarks' => $request->remarks,
        ]);

        PfApprovalWorkflow::create([
            'model_type' => get_class($loan),
            'model_id' => $loan->id,
            'approver_id' => 1,
            'level' => 1,
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'PF Loan application submitted successfully.');
    }

    public function approve(Request $request, ProvidentFundLoan $loan)
    {
        $request->validate([
            'approved_amount' => 'required|numeric|min:0|max:'.$loan->amount
        ]);

        if (!$loan->employee || $loan->employee->is_pf_member != 1) {
            return redirect()->back()->with('error', 'The employee associated with this loan is no longer an active Provident Fund member.');
        }

        $loan->approved_amount = $request->approved_amount;
        $loan->monthly_installment = $request->approved_amount / $loan->installments;
        $loan->outstanding_balance = $request->approved_amount;
        $loan->status = 'Approved';
        $loan->workflow_status = 'Approved';
        $loan->disbursement_date = now();
        $loan->save();

        return redirect()->back()->with('success', 'Loan approved successfully.');
    }

    public function disburse(Request $request, ProvidentFundLoan $loan)
    {
        DB::beginTransaction();
        try {
            if (!$loan->employee || $loan->employee->is_pf_member != 1) {
                return redirect()->back()->with('error', 'The employee associated with this loan is no longer an active Provident Fund member.');
            }

            $loan->status = 'Disbursed';
            $loan->save();

            $disburseAmount = $loan->approved_amount ?? $loan->amount;
            $lastBalance = $this->getCurrentBalance($loan->employee_id);
            PfLedger::create([
                'employee_id' => $loan->employee_id,
                'branch_id' => $loan->branch_id,
                'transaction_type' => 'loan_disbursement',
                'credit' => 0,
                'debit' => $disburseAmount,
                'balance' => $lastBalance - $disburseAmount,
                'description' => 'PF Loan Disbursement',
                'reference_type' => get_class($loan),
                'reference_id' => $loan->id,
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Loan disbursed and ledger updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Disbursement failed: ' . $e->getMessage());
        }
    }

    private function getCurrentBalance($employeeId)
    {
        $lastLedger = PfLedger::where('employee_id', $employeeId)->orderBy('id', 'desc')->first();
        return $lastLedger ? $lastLedger->balance : 0;
    }
}
