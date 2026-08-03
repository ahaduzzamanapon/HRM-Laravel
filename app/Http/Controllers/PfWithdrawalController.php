<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PfWithdrawal;
use App\Models\PfApprovalWorkflow;
use App\Models\PfLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PfWithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $withdrawalsQuery = PfWithdrawal::with(['employee'])->orderBy('created_at', 'desc');
        $employeesQuery = \App\Models\User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $withdrawalsQuery->whereHas('employee', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
            $employeesQuery->where('branch_id', $user->branch_id);
        }

        $withdrawals = $withdrawalsQuery->paginate(20);
        $employees = $employeesQuery->get();

        return view('pf.withdrawals.index', compact('withdrawals', 'employees'));
    }

    public function create()
    {
        return view('pf.withdrawals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:500',
            'reason' => 'required|string'
        ]);

        $employee = \App\Models\User::findOrFail($request->employee_id);

        if ($employee->is_pf_member != 1) {
            return redirect()->back()->with('error', 'Selected employee is not registered as a Provident Fund member.');
        }

        $withdrawal = PfWithdrawal::create([
            'employee_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        PfApprovalWorkflow::create([
            'model_type' => get_class($withdrawal),
            'model_id' => $withdrawal->id,
            'approver_id' => 1,
            'level' => 1,
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'PF Withdrawal request submitted.');
    }

    public function approve(Request $request, PfWithdrawal $withdrawal)
    {
        $request->validate([
            'approved_amount' => 'required|numeric|min:0|max:'.$withdrawal->amount
        ]);

        if (!$withdrawal->employee || $withdrawal->employee->is_pf_member != 1) {
            return redirect()->back()->with('error', 'The employee associated with this withdrawal is no longer an active Provident Fund member.');
        }

        $withdrawal->approved_amount = $request->approved_amount;
        $withdrawal->status = 'HR Approved';
        $withdrawal->save();
        
        return redirect()->back()->with('success', 'Withdrawal approved.');
    }

    public function disburse(Request $request, PfWithdrawal $withdrawal)
    {
        DB::beginTransaction();
        try {
            if (!$withdrawal->employee || $withdrawal->employee->is_pf_member != 1) {
                return redirect()->back()->with('error', 'The employee associated with this withdrawal is no longer an active Provident Fund member.');
            }

            $withdrawal->status = 'Disbursed';
            $withdrawal->disbursement_date = now();
            $withdrawal->save();

            $disburseAmount = $withdrawal->approved_amount ?? $withdrawal->amount;
            $lastBalance = $this->getCurrentBalance($withdrawal->employee_id);
            PfLedger::create([
                'employee_id' => $withdrawal->employee_id,
                'branch_id' => $withdrawal->branch_id,
                'transaction_type' => 'withdrawal',
                'credit' => 0,
                'debit' => $disburseAmount,
                'balance' => $lastBalance - $disburseAmount,
                'description' => 'PF ' . $withdrawal->type . ' Withdrawal',
                'reference_type' => get_class($withdrawal),
                'reference_id' => $withdrawal->id,
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Withdrawal disbursed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    private function getCurrentBalance($employeeId)
    {
        $lastLedger = PfLedger::where('employee_id', $employeeId)->orderBy('id', 'desc')->first();
        return $lastLedger ? $lastLedger->balance : 0;
    }
}
