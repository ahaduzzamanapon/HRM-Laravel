<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PfSettlement;
use App\Models\PfLedger;
use Illuminate\Support\Facades\DB;

class PfSettlementController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $settlementsQuery = PfSettlement::with('employee');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $settlementsQuery->whereHas('employee', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }

        $settlements = $settlementsQuery->paginate(20);
        return view('pf.settlements.index', compact('settlements'));
    }

    public function create()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $employeesQuery = \App\Models\User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $employeesQuery->where('branch_id', $user->branch_id);
        }

        $employees = $employeesQuery->get();
        return view('pf.settlements.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'reason' => 'required|string'
        ]);

        $employee = \App\Models\User::findOrFail($request->employee_id);
        if ($employee->is_pf_member != 1) {
            return redirect()->back()->with('error', 'Selected employee is not registered as a Provident Fund member.');
        }

        $balance = $this->getCurrentBalance($request->employee_id);

        PfSettlement::create([
            'employee_id' => $request->employee_id,
            'total_balance' => $balance,
            'settlement_amount' => $balance,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        return redirect()->route('pf.settlements.index')->with('success', 'Settlement initiated.');
    }

    public function process(Request $request, PfSettlement $settlement)
    {
        DB::beginTransaction();
        try {
            if (!$settlement->employee || $settlement->employee->is_pf_member != 1) {
                return redirect()->back()->with('error', 'The employee is no longer an active Provident Fund member. Settlement aborted.');
            }

            $settlement->status = 'Settled';
            $settlement->settlement_date = now();
            $settlement->save();

            PfLedger::create([
                'employee_id' => $settlement->employee_id,
                'branch_id' => $settlement->branch_id,
                'transaction_type' => 'settlement',
                'credit' => 0,
                'debit' => $settlement->settlement_amount,
                'balance' => 0,
                'description' => 'Final PF Settlement: ' . $settlement->reason,
                'reference_type' => get_class($settlement),
                'reference_id' => $settlement->id,
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Account settled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function getCurrentBalance($employeeId)
    {
        $lastLedger = PfLedger::where('employee_id', $employeeId)->orderBy('id', 'desc')->first();
        return $lastLedger ? $lastLedger->balance : 0;
    }
}
