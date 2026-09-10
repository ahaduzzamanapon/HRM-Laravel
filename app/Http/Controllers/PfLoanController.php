<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProvidentFundLoan;
use App\Models\PfApprovalWorkflow;
use App\Models\PfLedger;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PfLoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $loansQuery = ProvidentFundLoan::with(['employee'])->orderBy('created_at', 'desc');
        $employeesQuery = \App\Models\User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if (AuthorizationEngine::isEmployeeRole($user)) {
            $loansQuery->where('employee_id', $user->id);
            $employeesQuery->where('id', $user->id);
        } elseif ($user->role && $user->role->name !== 'Super Admin') {
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
        $user = Auth::user();
        if (AuthorizationEngine::isEmployeeRole($user)) {
            $request->merge(['employee_id' => $user->id]);
        }

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

    public function update(Request $request, ProvidentFundLoan $loan)
    {
        $user = Auth::user();
        $isEmployee = AuthorizationEngine::isEmployeeRole($user);

        if ($isEmployee) {
            if ($loan->employee_id != $user->id || $loan->status !== 'Pending') {
                abort(403, 'Unauthorized action. You can only edit your own pending loan applications.');
            }
        }

        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'installments' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        $amount = (float) $request->amount;
        $installments = (int) $request->installments;

        $loan->amount = $amount;
        $loan->installments = $installments;
        $loan->monthly_installment = $amount / $installments;
        if ($loan->status === 'Pending') {
            $loan->outstanding_balance = $amount;
        }
        if ($request->filled('status') && !$isEmployee) {
            $loan->status = $request->status;
        }
        if ($request->has('remarks')) {
            $loan->remarks = $request->remarks;
        }
        $loan->save();

        return redirect()->back()->with('success', 'PF Loan details updated successfully.');
    }

    public function destroy(ProvidentFundLoan $loan)
    {
        $user = Auth::user();
        if (AuthorizationEngine::isEmployeeRole($user)) {
            if ($loan->employee_id != $user->id || $loan->status !== 'Pending') {
                abort(403, 'Unauthorized action. You can only cancel your own pending loan applications.');
            }
        }

        $loan->delete();
        return redirect()->back()->with('success', 'PF Loan record deleted successfully.');
    }

    public function approve(Request $request, ProvidentFundLoan $loan)
    {
        $user = Auth::user();
        if (AuthorizationEngine::isEmployeeRole($user)) {
            abort(403, 'Unauthorized action.');
        }

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
        $user = Auth::user();
        if (AuthorizationEngine::isEmployeeRole($user)) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            if (!$loan->employee || $loan->employee->is_pf_member != 1) {
                return redirect()->back()->with('error', 'The employee associated with this loan is no longer an active Provident Fund member.');
            }

            $loan->status = 'Disbursed';
            $loan->workflow_status = 'Disbursed';
            $loan->save();

            DB::commit();
            return redirect()->back()->with('success', 'Loan disbursed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Disbursement failed: ' . $e->getMessage());
        }
    }
}
