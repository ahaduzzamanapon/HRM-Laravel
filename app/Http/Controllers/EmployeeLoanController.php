<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanType;
use App\Models\LoanSchedule;
use App\Models\LoanApproval;
use App\Models\User;
use Illuminate\Http\Request;
use Flash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeLoanController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $isEmployee = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);
        $canManageLoans = !$isEmployee && (isSuperAdmin() || \App\Services\AuthorizationEngine::isHRRole($authUser) || can('approve_loans') || can('disburse_loans') || can('manage_loans') || can('loans_and_advances') || can('edit_loans') || can('loans'));

        $query = Loan::with(['employee.branch', 'employee.department', 'loanType'])->orderBy('created_at', 'desc');

        if ($isEmployee) {
            $query->where('employee_id', $authUser->id);
        } else {
            applyUserBranchScope($query, 'employee');

            if ($request->filled('branch_id')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                });
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('required_month')) {
                $reqM = Carbon::parse($request->required_month)->format('Y-m-01');
                $query->where('required_month', $reqM);
            }
            if ($request->filled('effective_month')) {
                $effM = Carbon::parse($request->effective_month)->format('Y-m-01');
                $query->where('effective_month', $effM);
            }
        }

        $loans = $query->paginate(15);
        $loanTypes = LoanType::all();

        $empQuery = User::where('status', '!=', 'retired');
        if (!$isEmployee) {
            applyBranchScope($empQuery, 'branch_id');
        } else {
            $empQuery->where('id', $authUser->id);
        }
        $employees = $empQuery->orderBy('name', 'asc')->with('salaryGrade')->select('id', 'name', 'last_name', 'emp_id', 'branch_id', 'salary_grade_id')->get();

        // Dashboard Summary Metrics
        $metricsQuery = Loan::query();
        if ($isEmployee) {
            $metricsQuery->where('employee_id', $authUser->id);
        } else {
            applyUserBranchScope($metricsQuery, 'employee');
        }

        $totalActiveLoans = (clone $metricsQuery)->whereIn('status', ['Approved', 'Disbursed', 'Active Repayment'])->where('outstanding_balance', '>', 0)->count();
        $pendingDisbursementCount = (clone $metricsQuery)->whereIn('status', ['Approved', 'Pending Disbursement'])->count();
        $nextMonth = Carbon::now()->addMonth()->format('Y-m-01');
        $loansStartingNextMonth = (clone $metricsQuery)->where('effective_month', $nextMonth)->count();
        $totalOutstandingAmount = (clone $metricsQuery)->whereIn('status', ['Approved', 'Disbursed', 'Active Repayment', 'Repaid'])->sum('outstanding_balance');
        $totalMonthlyDeductions = (clone $metricsQuery)->whereIn('status', ['Approved', 'Disbursed', 'Active Repayment'])->where('outstanding_balance', '>', 0)->sum('monthly_installment');
        $completedThisMonth = (clone $metricsQuery)->where('status', 'Completed')->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month)->count();

        $metrics = [
            'totalActiveLoans' => $totalActiveLoans,
            'pendingDisbursementCount' => $pendingDisbursementCount,
            'loansStartingNextMonth' => $loansStartingNextMonth,
            'totalOutstandingAmount' => $totalOutstandingAmount,
            'totalMonthlyDeductions' => $totalMonthlyDeductions,
            'completedThisMonth' => $completedThisMonth,
        ];

        return view('loans.index', compact('loans', 'loanTypes', 'employees', 'canManageLoans', 'metrics'));
    }

    public function create()
    {
        $loanTypes = LoanType::all();
        $authUser = Auth::user();
        $isEmployee = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);
        $canManageLoans = !$isEmployee && (isSuperAdmin() || \App\Services\AuthorizationEngine::isHRRole($authUser) || can('approve_loans') || can('manage_loans') || can('loans_and_advances') || can('loans'));

        $empQuery = User::where('status', '!=', 'retired');
        if (!$isEmployee) {
            applyBranchScope($empQuery, 'branch_id');
        } else {
            $empQuery->where('id', $authUser->id);
        }
        $employees = $empQuery->orderBy('name', 'asc')->with('salaryGrade')->select('id', 'name', 'last_name', 'emp_id', 'branch_id', 'salary_grade_id')->get();

        return view('loans.create', compact('loanTypes', 'employees', 'canManageLoans'));
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();
        $isEmployee = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);
        $canManageLoans = !$isEmployee && (isSuperAdmin() || \App\Services\AuthorizationEngine::isHRRole($authUser) || can('approve_loans') || can('manage_loans') || can('loans_and_advances') || can('loans'));

        $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:1000',
            'installments' => 'required|integer|min:1',
            'required_month' => 'required|string',
            'effective_month' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $reqMonthFormatted = Carbon::parse($request->required_month)->format('Y-m-01');
        $effMonthFormatted = Carbon::parse($request->effective_month)->format('Y-m-01');

        if (strtotime($effMonthFormatted) < strtotime($reqMonthFormatted)) {
            Flash::error('Loan Effective Month (first repayment month) cannot be earlier than Loan Required Month.');
            return redirect()->back()->withInput();
        }

        $targetEmployeeId = $request->filled('employee_id') ? $request->employee_id : Auth::id();

        if (!isSuperAdmin() && $request->filled('employee_id')) {
            $targetUser = User::find($request->employee_id);
            $userBranch = userBranchId();
            if ($targetUser && $userBranch && (int)$targetUser->branch_id !== (int)$userBranch) {
                Flash::error('You do not have permission to apply loan for an employee outside your branch.');
                return redirect()->back();
            }
        }

        $targetEmployee = User::with('salaryGrade')->find($targetEmployeeId);

        if (!$targetEmployee) {
            Flash::error('Employee not found.');
            return redirect()->back();
        }

        $loanType = LoanType::findOrFail($request->loan_type_id);
        $principal = (float) $request->amount;
        $numInstallments = (int) $request->installments;
        $rate = (float) ($loanType->interest_rate ?? 0);

        // 1. Validate max installments
        if (!empty($loanType->max_installments) && $numInstallments > (int)$loanType->max_installments) {
            Flash::error("Requested installments ({$numInstallments} months) exceeds the maximum limit of {$loanType->max_installments} months allowed for category '{$loanType->name}'.");
            return redirect()->back()->withInput();
        }

        // 2. Validate loan ceiling by grade
        $ceilings = $loanType->loan_ceilings ?? [];
        if (!empty($ceilings) && is_array($ceilings)) {
            $empGradeName = $targetEmployee->salaryGrade->grade ?? null;
            $maxCeiling = null;
            $matchingGradeLabel = null;

            if ($empGradeName) {
                foreach ($ceilings as $c) {
                    if (isset($c['grade']) && isset($c['amount'])) {
                        $cGrade = trim((string)$c['grade']);
                        $eGrade = trim((string)$empGradeName);
                        if (strcasecmp($cGrade, $eGrade) === 0 || strcasecmp(str_replace('Grade ', '', $cGrade), str_replace('Grade ', '', $eGrade)) === 0) {
                            $maxCeiling = (float)$c['amount'];
                            $matchingGradeLabel = $cGrade;
                            break;
                        }
                    }
                }
            }

            if ($maxCeiling === null) {
                foreach ($ceilings as $c) {
                    if (isset($c['amount']) && is_numeric($c['amount'])) {
                        $amt = (float)$c['amount'];
                        if ($maxCeiling === null || $amt > $maxCeiling) {
                            $maxCeiling = $amt;
                        }
                    }
                }
            }

            if ($maxCeiling !== null && $principal > $maxCeiling) {
                $gradeText = $matchingGradeLabel ? " for {$matchingGradeLabel}" : "";
                Flash::error("Requested loan amount (৳ " . number_format($principal, 2) . ") exceeds the maximum ceiling limit of ৳ " . number_format($maxCeiling, 2) . "{$gradeText} for category '{$loanType->name}'.");
                return redirect()->back()->withInput();
            }
        }

        // EMI Calculation (Flat or Simple Interest)
        $totalInterest = ($principal * ($rate / 100)) * ($numInstallments / 12);
        $totalAmount = $principal + $totalInterest;
        $monthlyInstallment = round($totalAmount / $numInstallments, 2);

        $applicationNo = 'LN-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $loan = Loan::create([
            'application_no' => $applicationNo,
            'employee_id' => $targetEmployee->id,
            'branch_id' => $targetEmployee->branch_id,
            'loan_type_id' => $loanType->id,
            'amount' => $principal,
            'interest_rate' => $rate,
            'installments' => $numInstallments,
            'monthly_installment' => $monthlyInstallment,
            'outstanding_balance' => $totalAmount,
            'required_month' => $reqMonthFormatted,
            'effective_month' => $effMonthFormatted,
            'next_deduction_month' => $effMonthFormatted,
            'status' => 'Pending',
            'remarks' => $request->remarks,
        ]);

        // Notify HR/Admin/Super Admin users about the new loan application (strictly excluding employee roles role_id=3 / group_id=3)
        try {
            $hrAdminUsers = User::where(function ($q) {
                $q->whereIn('group_id', [1, 2, 5])
                  ->orWhereIn('role_id', [1, 2, 5])
                  ->orWhere('status', 'admin')
                  ->orWhereHas('role', function ($r) {
                      $r->whereIn('name', ['Admin', 'Super Admin', 'HR', 'HR Manager', 'Branch Admin', 'Branch Manager']);
                  });
            })->get();

            if ($targetEmployee->branch_id) {
                $branchAdmins = User::where('branch_id', $targetEmployee->branch_id)
                    ->whereIn('group_id', [1, 2, 5])
                    ->get();
                $hrAdminUsers = $hrAdminUsers->merge($branchAdmins)->unique('id');
            }

            $adminsToNotify = $hrAdminUsers->reject(function ($u) use ($authUser) {
                // Strictly exclude employee roles (role_id=3 or group_id=3)
                if ((isset($u->role_id) && (int)$u->role_id === 3) || (isset($u->group_id) && (int)$u->group_id === 3)) {
                    return true;
                }
                if (\App\Services\AuthorizationEngine::isEmployeeRole($u)) {
                    return true;
                }
                return (int)$u->id === (int)$authUser->id;
            });

            if ($adminsToNotify->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($adminsToNotify, new \App\Notifications\LoanAppliedNotification($loan));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Loan Applied Notification Error: ' . $e->getMessage());
        }

        Flash::success('Loan application submitted successfully.');
        return redirect()->route('employeeLoans.index');
    }

    public function show($id)
    {
        $loan = Loan::with(['employee.branch', 'employee.department', 'employee.salaryGrade', 'loanType', 'loanRepayments.creator'])->findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('disburse_loans') || can('manage_loans') || can('edit_loans');

        if ($authUser) {
            try {
                $authUser->unreadNotifications->each(function ($n) use ($id) {
                    $d = $n->data;
                    if (isset($d['loan_id']) && (int)$d['loan_id'] === (int)$id) {
                        $n->markAsRead();
                    }
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Notification Mark As Read Error: ' . $e->getMessage());
            }
        }

        $schedules = LoanSchedule::where('loan_id', $loan->id)->orderBy('installment_no', 'asc')->get();
        $approvals = LoanApproval::with('approver')->where('loan_id', $loan->id)->get();
        $repayments = \App\Models\LoanRepayment::with(['employee', 'creator'])->where('loan_id', $loan->id)->orderBy('payroll_month', 'desc')->get();

        return view('loans.show', compact('loan', 'schedules', 'approvals', 'repayments', 'canManageLoans'));
    }

    public function edit($id)
    {
        $loan = Loan::with(['employee.salaryGrade', 'loanType'])->findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans') || can('edit_loans');
        $isOwner = (int)$loan->employee_id === (int)$authUser->id;

        if (!$canManageLoans && !$isOwner) {
            abort(403, 'Unauthorized access to this loan application.');
        }

        if (in_array(strtolower($loan->status), ['disbursed', 'active repayment', 'repaying', 'completed'])) {
            Flash::error('Disbursed or processed loan applications cannot be edited.');
            return redirect()->route('employeeLoans.index');
        }

        if ($isOwner && !isSuperAdmin() && strtolower($loan->status) !== 'pending') {
            Flash::error('Approved or processed loan applications cannot be edited.');
            return redirect()->route('employeeLoans.index');
        }

        if (!$isOwner && $loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $loanTypes = LoanType::all();
        $empQuery = User::where('status', '!=', 'retired');
        if (!isSuperAdmin()) {
            applyBranchScope($empQuery, 'branch_id');
        }
        $employees = $empQuery->orderBy('name', 'asc')->with('salaryGrade')->select('id', 'name', 'last_name', 'emp_id', 'branch_id', 'salary_grade_id')->get();

        return view('loans.edit', compact('loan', 'loanTypes', 'employees', 'canManageLoans'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans') || can('edit_loans');
        $isOwner = (int)$loan->employee_id === (int)$authUser->id;

        if (!$canManageLoans && !$isOwner) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array(strtolower($loan->status), ['disbursed', 'active repayment', 'repaying', 'completed'])) {
            Flash::error('Disbursed or processed loan applications cannot be edited.');
            return redirect()->route('employeeLoans.index');
        }

        if ($isOwner && !isSuperAdmin() && strtolower($loan->status) !== 'pending') {
            Flash::error('Approved or processed loan applications cannot be edited.');
            return redirect()->route('employeeLoans.index');
        }

        if (!$isOwner && $loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:1000',
            'installments' => 'required|integer|min:1',
            'required_month' => 'required|string',
            'effective_month' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $reqMonthFormatted = Carbon::parse($request->required_month)->format('Y-m-01');
        $effMonthFormatted = Carbon::parse($request->effective_month)->format('Y-m-01');

        if (strtotime($effMonthFormatted) < strtotime($reqMonthFormatted)) {
            Flash::error('Loan Effective Month (first repayment month) cannot be earlier than Loan Required Month.');
            return redirect()->back()->withInput();
        }

        $loanType = LoanType::findOrFail($request->loan_type_id);
        $principal = (float) $request->amount;
        $numInstallments = (int) $request->installments;
        $rate = (float) ($loanType->interest_rate ?? 0);

        // 1. Validate max installments
        if (!empty($loanType->max_installments) && $numInstallments > (int)$loanType->max_installments) {
            Flash::error("Requested installments ({$numInstallments} months) exceeds the maximum limit of {$loanType->max_installments} months allowed for category '{$loanType->name}'.");
            return redirect()->back()->withInput();
        }

        $targetEmployeeId = $request->filled('employee_id') ? $request->employee_id : $loan->employee_id;
        $targetEmployee = User::with('salaryGrade')->find($targetEmployeeId);

        // 2. Validate loan ceiling by grade
        $ceilings = $loanType->loan_ceilings ?? [];
        if (!empty($ceilings) && is_array($ceilings) && $targetEmployee) {
            $empGradeName = $targetEmployee->salaryGrade->grade ?? null;
            $maxCeiling = null;
            $matchingGradeLabel = null;

            if ($empGradeName) {
                foreach ($ceilings as $c) {
                    if (isset($c['grade']) && isset($c['amount'])) {
                        $cGrade = trim((string)$c['grade']);
                        $eGrade = trim((string)$empGradeName);
                        if (strcasecmp($cGrade, $eGrade) === 0 || strcasecmp(str_replace('Grade ', '', $cGrade), str_replace('Grade ', '', $eGrade)) === 0) {
                            $maxCeiling = (float)$c['amount'];
                            $matchingGradeLabel = $cGrade;
                            break;
                        }
                    }
                }
            }

            if ($maxCeiling === null) {
                foreach ($ceilings as $c) {
                    if (isset($c['amount']) && is_numeric($c['amount'])) {
                        $amt = (float)$c['amount'];
                        if ($maxCeiling === null || $amt > $maxCeiling) {
                            $maxCeiling = $amt;
                        }
                    }
                }
            }

            if ($maxCeiling !== null && $principal > $maxCeiling) {
                $gradeText = $matchingGradeLabel ? " for {$matchingGradeLabel}" : "";
                Flash::error("Requested loan amount (৳ " . number_format($principal, 2) . ") exceeds the maximum ceiling limit of ৳ " . number_format($maxCeiling, 2) . "{$gradeText} for category '{$loanType->name}'.");
                return redirect()->back()->withInput();
            }
        }

        $totalInterest = ($principal * ($rate / 100)) * ($numInstallments / 12);
        $totalAmount = $principal + $totalInterest;
        $monthlyInstallment = round($totalAmount / $numInstallments, 2);

        $updateData = [
            'loan_type_id' => $loanType->id,
            'amount' => $principal,
            'interest_rate' => $rate,
            'installments' => $numInstallments,
            'monthly_installment' => $monthlyInstallment,
            'outstanding_balance' => $totalAmount,
            'required_month' => $reqMonthFormatted,
            'effective_month' => $effMonthFormatted,
            'remarks' => $request->remarks,
        ];

        if ($request->filled('employee_id')) {
            if (!isSuperAdmin()) {
                $targetUser = User::find($request->employee_id);
                $userBranch = userBranchId();
                if ($targetUser && $userBranch && (int)$targetUser->branch_id !== (int)$userBranch) {
                    Flash::error('You do not have permission to select an employee outside your branch.');
                    return redirect()->back();
                }
            }
            $updateData['employee_id'] = $request->employee_id;
            $targetUser = User::find($request->employee_id);
            if ($targetUser) {
                $updateData['branch_id'] = $targetUser->branch_id;
            }
        }

        $oldAmount = (float)$loan->amount;

        $loan->update($updateData);

        // Notify employee if admin modified the requested loan amount
        if ($canManageLoans && (int)$authUser->id !== (int)$loan->employee_id && abs($oldAmount - $principal) > 0.01) {
            try {
                if ($loan->employee) {
                    $loan->employee->notify(new \App\Notifications\LoanAmountModifiedNotification($loan, $oldAmount, $principal));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Loan Amount Modified Notification Error: ' . $e->getMessage());
            }
        }

        Flash::success('Loan application updated successfully.');
        return redirect()->route('employeeLoans.index');
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans');
        $isOwner = (int)$loan->employee_id === (int)$authUser->id;

        if (!$canManageLoans && !$isOwner) {
            abort(403, 'Unauthorized action.');
        }

        if ($isOwner && !isSuperAdmin() && strtolower($loan->status) !== 'pending') {
            Flash::error('Approved or processed loan applications cannot be deleted.');
            return redirect()->route('employeeLoans.index');
        }

        if (!$isOwner && $loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $loan->delete();
        Flash::success('Loan application deleted successfully.');
        return redirect()->route('employeeLoans.index');
    }

    public function approve($id)
    {
        if (!isSuperAdmin() && !can('approve_loans')) {
            abort(403, 'Unauthorized action.');
        }

        $loan = Loan::with('employee')->findOrFail($id);
        if ($loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $loan->status = 'Approved';
        $loan->save();

        LoanApproval::create([
            'loan_id' => $loan->id,
            'approver_id' => Auth::id(),
            'level' => 'HR/Accounts Manager',
            'status' => 'Approved',
            'remarks' => 'Approved via Loan Management',
        ]);

        try {
            if ($loan->employee) {
                $loan->employee->notify(new \App\Notifications\LoanStatusUpdatedNotification($loan, 'Approved'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Loan Approval Notification Error: ' . $e->getMessage());
        }

        Flash::success('Loan application approved successfully.');
        return redirect()->route('employeeLoans.show', $loan->id);
    }

    public function disburse($id, Request $request)
    {
        if (!isSuperAdmin() && !can('disburse_loans')) {
            abort(403, 'Unauthorized action.');
        }

        $loan = Loan::with('employee')->findOrFail($id);
        if ($loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $loan->status = 'Disbursed';
        $loan->disbursement_date = now();
        $loan->payment_method = $request->input('payment_method', 'Bank Transfer');
        $loan->voucher_no = 'VCH-' . rand(10000, 99999);
        $loan->save();

        // Generate EMI Schedule breakdown
        $principal = $loan->amount;
        $numInstallments = $loan->installments;
        $rate = $loan->interest_rate;

        $principalPerMonth = round($principal / $numInstallments, 2);
        $interestPerMonth = round(($loan->outstanding_balance - $principal) / $numInstallments, 2);
        $totalInstallment = $principalPerMonth + $interestPerMonth;

        $dueDate = $loan->effective_month ? Carbon::parse($loan->effective_month) : Carbon::now()->addMonth();

        for ($i = 1; $i <= $numInstallments; $i++) {
            LoanSchedule::create([
                'loan_id' => $loan->id,
                'installment_no' => $i,
                'due_date' => $dueDate->format('Y-m-d'),
                'principal_amount' => $principalPerMonth,
                'interest_amount' => $interestPerMonth,
                'total_installment' => $totalInstallment,
                'paid_amount' => 0,
                'status' => 'Pending',
            ]);
            $dueDate->addMonth();
        }

        try {
            if ($loan->employee) {
                $loan->employee->notify(new \App\Notifications\LoanStatusUpdatedNotification($loan, 'Disbursed'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Loan Disbursement Notification Error: ' . $e->getMessage());
        }

        Flash::success('Loan disbursed successfully and EMI schedule generated.');
        return redirect()->route('employeeLoans.show', $loan->id);
    }

    public function reject($id, Request $request)
    {
        if (!isSuperAdmin() && !can('approve_loans')) {
            abort(403, 'Unauthorized action.');
        }

        $loan = Loan::with('employee')->findOrFail($id);
        if ($loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $loan->status = 'Rejected';
        $loan->remarks = $request->input('remarks', 'Loan application rejected');
        $loan->save();

        try {
            if ($loan->employee) {
                $loan->employee->notify(new \App\Notifications\LoanStatusUpdatedNotification($loan, 'Rejected'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Loan Rejection Notification Error: ' . $e->getMessage());
        }

        Flash::success('Loan application rejected.');
        return redirect()->route('employeeLoans.show', $loan->id);
    }
}
