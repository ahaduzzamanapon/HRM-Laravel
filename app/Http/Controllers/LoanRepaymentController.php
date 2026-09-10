<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanRepayment;
use App\Models\Loan;
use App\Models\User;
use Flash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoanRepaymentController extends Controller
{
    /**
     * Display a listing of the loan repayments with summary metrics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = LoanRepayment::with(['loan.employee.branch', 'loan.employee.department', 'loan.loanType'])->orderBy('repayment_date', 'desc');

        // Filter repayments where employee belongs to user's branch
        if (!isSuperAdmin()) {
            $branchId = userBranchId();
            if ($branchId) {
                $query->whereHas('loan.employee', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        }

        $loanRepayments = $query->paginate(15);

        // Dashboard Summary Metrics
        $loansQuery = Loan::query();
        if (!isSuperAdmin()) {
            applyUserBranchScope($loansQuery, 'employee');
        }

        $totalCollectedAmount = (clone $query)->sum('amount');
        $totalTransactionsCount = (clone $query)->count();
        $activeLoansCount = (clone $loansQuery)->whereIn('status', ['Approved', 'Disbursed', 'Active Repayment'])->count();
        $totalOutstandingAmount = (clone $loansQuery)->whereIn('status', ['Approved', 'Disbursed', 'Active Repayment', 'Repaid'])->sum('outstanding_balance');

        $metrics = [
            'totalCollectedAmount' => $totalCollectedAmount,
            'totalTransactionsCount' => $totalTransactionsCount,
            'activeLoansCount' => $activeLoansCount,
            'totalOutstandingAmount' => $totalOutstandingAmount,
        ];

        return view('loan_repayments.index', compact('loanRepayments', 'metrics'));
    }

    /**
     * Show the form for creating a new loan repayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loanQuery = Loan::with(['employee.branch', 'employee.department', 'loanType', 'loanRepayments']);

        if (!isSuperAdmin()) {
            $branchId = userBranchId();
            if ($branchId) {
                $loanQuery->whereHas('employee', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        }

        // Only allow active/approved/disbursed loans
        $loansList = $loanQuery->whereIn('status', ['Approved', 'Disbursed', 'Active Repayment', 'Pending Disbursement'])->get();

        $loansMap = [];
        $loansDetailsMap = [];

        foreach ($loansList as $l) {
            $appNo = $l->application_no ?? ('LN-' . $l->id);
            $empName = $l->employee ? trim($l->employee->name . ' ' . ($l->employee->last_name ?? '')) : 'N/A';
            $typeName = optional($l->loanType)->name ?? 'Staff Loan';
            $balFormatted = number_format($l->outstanding_balance, 2);

            $loansMap[$l->id] = "[{$appNo}] {$empName} — {$typeName} (Bal: ৳ {$balFormatted})";

            $totalPaid = (float)($l->paid_amount ?? $l->loanRepayments->sum('amount'));
            $nextDueDate = $l->effective_month ? Carbon::parse($l->effective_month)->format('M Y') : 'N/A';

            $loansDetailsMap[$l->id] = [
                'id' => $l->id,
                'application_no' => $appNo,
                'employee_name' => $empName,
                'emp_id' => $l->employee->emp_id ?? 'N/A',
                'branch_name' => optional($l->employee->branch)->branch_name ?? 'N/A',
                'department_name' => optional($l->employee->department)->name ?? 'N/A',
                'loan_type_name' => $typeName,
                'principal_amount' => (float)$l->amount,
                'installments' => (int)$l->installments,
                'monthly_installment' => (float)$l->monthly_installment,
                'total_paid' => $totalPaid,
                'outstanding_balance' => (float)$l->outstanding_balance,
                'effective_month' => $nextDueDate,
                'status' => $l->status,
                'employee_id' => $l->employee_id,
            ];
        }

        return view('loan_repayments.create', compact('loansMap', 'loansDetailsMap'));
    }

    /**
     * Store a newly created loan repayment in storage and recalculate balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $loan = Loan::with('employee')->findOrFail($request->loan_id);

        if (!isSuperAdmin() && $loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $repaymentAmount = (float)$request->amount;

        // Prevent repayment from exceeding outstanding balance
        if ($repaymentAmount > ($loan->outstanding_balance + 1.00)) {
            Flash::error("Repayment amount (৳ " . number_format($repaymentAmount, 2) . ") cannot exceed current outstanding loan balance (৳ " . number_format($loan->outstanding_balance, 2) . ").");
            return redirect()->back()->withInput();
        }

        $repaymentDate = $request->filled('repayment_date') ? Carbon::parse($request->repayment_date)->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');
        $payrollMonth = Carbon::parse($repaymentDate)->format('Y-m-01');

        $repayment = LoanRepayment::create([
            'loan_id' => $loan->id,
            'employee_id' => $loan->employee_id,
            'amount' => $repaymentAmount,
            'installment_amount' => $repaymentAmount,
            'principal_paid' => $repaymentAmount,
            'remaining_balance' => max(0, round($loan->outstanding_balance - $repaymentAmount, 2)),
            'repayment_date' => $repaymentDate,
            'payroll_month' => $payrollMonth,
            'remarks' => $request->remarks ?? ('Manual repayment for ' . ($loan->application_no ?? 'LN-' . $loan->id)),
            'created_by' => Auth::id(),
        ]);

        // Recalculate loan totals & status
        self::recalculateLoanTotals($loan);

        Flash::success("Loan Repayment of ৳ " . number_format($repaymentAmount, 2) . " saved successfully.");
        return redirect(route('loanRepayments.index'));
    }

    /**
     * Display the specified loan repayment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loanRepayment = LoanRepayment::with(['loan.employee.branch', 'loan.employee.department', 'loan.loanType'])->find($id);

        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found.');
            return redirect(route('loanRepayments.index'));
        }

        return view('loan_repayments.show', compact('loanRepayment'));
    }

    /**
     * Show the form for editing the specified loan repayment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loanRepayment = LoanRepayment::with(['loan.employee', 'loan.loanType'])->find($id);

        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found.');
            return redirect(route('loanRepayments.index'));
        }

        $loanQuery = Loan::with(['employee.branch', 'employee.department', 'loanType', 'loanRepayments']);
        if (!isSuperAdmin()) {
            $branchId = userBranchId();
            if ($branchId) {
                $loanQuery->whereHas('employee', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        }

        $loansList = $loanQuery->get();
        $loansMap = [];
        $loansDetailsMap = [];

        foreach ($loansList as $l) {
            $appNo = $l->application_no ?? ('LN-' . $l->id);
            $empName = $l->employee ? trim($l->employee->name . ' ' . ($l->employee->last_name ?? '')) : 'N/A';
            $typeName = optional($l->loanType)->name ?? 'Staff Loan';
            $balFormatted = number_format($l->outstanding_balance, 2);

            $loansMap[$l->id] = "[{$appNo}] {$empName} — {$typeName} (Bal: ৳ {$balFormatted})";

            $totalPaid = (float)($l->paid_amount ?? $l->loanRepayments->sum('amount'));

            $loansDetailsMap[$l->id] = [
                'id' => $l->id,
                'application_no' => $appNo,
                'employee_name' => $empName,
                'emp_id' => $l->employee->emp_id ?? 'N/A',
                'branch_name' => optional($l->employee->branch)->branch_name ?? 'N/A',
                'department_name' => optional($l->employee->department)->name ?? 'N/A',
                'loan_type_name' => $typeName,
                'principal_amount' => (float)$l->amount,
                'installments' => (int)$l->installments,
                'monthly_installment' => (float)$l->monthly_installment,
                'total_paid' => $totalPaid,
                'outstanding_balance' => (float)$l->outstanding_balance,
                'effective_month' => $l->effective_month ? Carbon::parse($l->effective_month)->format('M Y') : 'N/A',
                'status' => $l->status,
                'employee_id' => $l->employee_id,
            ];
        }

        return view('loan_repayments.edit', compact('loanRepayment', 'loansMap', 'loansDetailsMap'));
    }

    /**
     * Update the specified loan repayment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loanRepayment = LoanRepayment::find($id);

        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found.');
            return redirect(route('loanRepayments.index'));
        }

        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $loan = Loan::with('employee')->findOrFail($request->loan_id);
        $repaymentAmount = (float)$request->amount;

        // Current loan balance excluding this current repayment
        $currentPaidExcluding = LoanRepayment::where('loan_id', $loan->id)->where('id', '!=', $id)->sum('amount');
        $principal = (float)$loan->amount;
        $rate = (float)($loan->interest_rate ?? 0);
        $numInstallments = (int)($loan->installments ?? 1);
        $totalCost = $principal + (($principal * ($rate / 100)) * ($numInstallments / 12));
        $maxAllowed = max(0, $totalCost - $currentPaidExcluding);

        if ($repaymentAmount > ($maxAllowed + 1.00)) {
            Flash::error("Updated repayment amount (৳ " . number_format($repaymentAmount, 2) . ") exceeds current maximum allowable balance (৳ " . number_format($maxAllowed, 2) . ").");
            return redirect()->back()->withInput();
        }

        $repaymentDate = $request->filled('repayment_date') ? Carbon::parse($request->repayment_date)->format('Y-m-d H:i:s') : $loanRepayment->repayment_date;

        $loanRepayment->update([
            'loan_id' => $loan->id,
            'employee_id' => $loan->employee_id,
            'amount' => $repaymentAmount,
            'installment_amount' => $repaymentAmount,
            'principal_paid' => $repaymentAmount,
            'remaining_balance' => max(0, round($maxAllowed - $repaymentAmount, 2)),
            'repayment_date' => $repaymentDate,
            'payroll_month' => Carbon::parse($repaymentDate)->format('Y-m-01'),
            'remarks' => $request->remarks,
        ]);

        // Recalculate loan totals & status
        self::recalculateLoanTotals($loan);

        Flash::success('Loan Repayment updated successfully.');
        return redirect(route('loanRepayments.index'));
    }

    /**
     * Remove the specified loan repayment from storage and update loan balance.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loanRepayment = LoanRepayment::find($id);

        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found.');
            return redirect(route('loanRepayments.index'));
        }

        $loan = Loan::find($loanRepayment->loan_id);

        $loanRepayment->delete();

        if ($loan) {
            self::recalculateLoanTotals($loan);
        }

        Flash::success('Loan Repayment deleted successfully.');
        return redirect(route('loanRepayments.index'));
    }

    /**
     * Recalculate total paid amount, outstanding balance, paid installments count and status for a loan.
     *
     * @param Loan $loan
     * @return void
     */
    public static function recalculateLoanTotals(Loan $loan)
    {
        $totalPaid = (float) LoanRepayment::where('loan_id', $loan->id)->sum('amount');
        $principal = (float) $loan->amount;
        $rate = (float) ($loan->interest_rate ?? 0);
        $numInstallments = (int) ($loan->installments ?? 1);

        $totalInterest = ($principal * ($rate / 100)) * ($numInstallments / 12);
        $totalCost = $principal + $totalInterest;

        $outstanding = max(0, round($totalCost - $totalPaid, 2));
        $paidCount = LoanRepayment::where('loan_id', $loan->id)->count();

        $loan->paid_amount = $totalPaid;
        $loan->outstanding_balance = $outstanding;
        $loan->paid_installments = $paidCount;

        if ($outstanding <= 0.01) {
            $loan->status = 'Completed';
        } elseif ($totalPaid > 0) {
            $loan->status = 'Active Repayment';
        }

        $loan->save();
    }
}
