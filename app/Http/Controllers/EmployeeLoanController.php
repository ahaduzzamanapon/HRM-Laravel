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
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('disburse_loans') || can('manage_loans');

        $query = Loan::with(['employee.branch', 'loanType'])->orderBy('created_at', 'desc');

        if (!$canManageLoans) {
            $query->where('employee_id', Auth::id());
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
        }

        $loans = $query->paginate(15);
        $loanTypes = LoanType::all();

        return view('loans.index', compact('loans', 'loanTypes', 'canManageLoans'));
    }

    public function create()
    {
        $loanTypes = LoanType::all();
        $authUser = Auth::user();

        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans');

        $employees = null;
        if ($canManageLoans) {
            $empQuery = User::where('group_id', '!=', 1)->where('status', 'active');
            applyBranchScope($empQuery, 'branch_id');
            $employees = $empQuery->select('id', 'name', 'last_name', 'emp_id')->get();
        }

        return view('loans.create', compact('loanTypes', 'employees', 'canManageLoans'));
    }

    public function store(Request $request)
    {
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans');

        $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:1000',
            'installments' => 'required|integer|min:1|max:120',
            'remarks' => 'nullable|string',
        ]);

        $targetEmployeeId = ($canManageLoans && $request->filled('employee_id')) ? $request->employee_id : Auth::id();
        $targetEmployee = User::find($targetEmployeeId);

        if (!$targetEmployee) {
            Flash::error('Employee not found.');
            return redirect()->back();
        }

        $loanType = LoanType::findOrFail($request->loan_type_id);
        $principal = (float) $request->amount;
        $numInstallments = (int) $request->installments;
        $rate = (float) ($loanType->interest_rate ?? 0);

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
            'status' => 'Pending',
            'remarks' => $request->remarks,
        ]);

        Flash::success('Loan application submitted successfully.');
        return redirect()->route('employeeLoans.index');
    }

    public function show($id)
    {
        $loan = Loan::with(['employee.branch', 'employee.department', 'loanType', 'loanRepayments'])->findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('disburse_loans') || can('manage_loans');

        if (!$canManageLoans && $loan->employee_id != $authUser->id) {
            abort(403, 'Unauthorized access to this loan application.');
        }

        if ($canManageLoans && $loan->employee) {
            checkBranchAccess($loan->employee->branch_id);
        }

        $schedules = LoanSchedule::where('loan_id', $loan->id)->orderBy('installment_no', 'asc')->get();
        $approvals = LoanApproval::with('approver')->where('loan_id', $loan->id)->get();

        return view('loans.show', compact('loan', 'schedules', 'approvals', 'canManageLoans'));
    }

    public function edit($id)
    {
        $loan = Loan::with(['employee', 'loanType'])->findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans');

        // Check ownership & status for regular employees
        if (!$canManageLoans) {
            if ($loan->employee_id != $authUser->id) {
                abort(403, 'Unauthorized access to this loan application.');
            }
            if (strtolower($loan->status) !== 'pending') {
                Flash::error('Approved or processed loan applications cannot be edited.');
                return redirect()->route('employeeLoans.index');
            }
        } else {
            if ($loan->employee) {
                checkBranchAccess($loan->employee->branch_id);
            }
        }

        $loanTypes = LoanType::all();
        $employees = null;
        if ($canManageLoans) {
            $empQuery = User::where('group_id', '!=', 1)->where('status', 'active');
            applyBranchScope($empQuery, 'branch_id');
            $employees = $empQuery->select('id', 'name', 'last_name', 'emp_id')->get();
        }

        return view('loans.edit', compact('loan', 'loanTypes', 'employees', 'canManageLoans'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans');

        if (!$canManageLoans) {
            if ($loan->employee_id != $authUser->id) {
                abort(403, 'Unauthorized action.');
            }
            if (strtolower($loan->status) !== 'pending') {
                Flash::error('Approved or processed loan applications cannot be edited.');
                return redirect()->route('employeeLoans.index');
            }
        } else {
            if ($loan->employee) {
                checkBranchAccess($loan->employee->branch_id);
            }
        }

        $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:1000',
            'installments' => 'required|integer|min:1|max:120',
            'remarks' => 'nullable|string',
        ]);

        $loanType = LoanType::findOrFail($request->loan_type_id);
        $principal = (float) $request->amount;
        $numInstallments = (int) $request->installments;
        $rate = (float) ($loanType->interest_rate ?? 0);

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
            'remarks' => $request->remarks,
        ];

        if ($canManageLoans && $request->filled('employee_id')) {
            $updateData['employee_id'] = $request->employee_id;
        }

        if ($canManageLoans && $request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        $loan->update($updateData);

        Flash::success('Loan application updated successfully.');
        return redirect()->route('employeeLoans.index');
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $authUser = Auth::user();
        $canManageLoans = isSuperAdmin() || can('approve_loans') || can('manage_loans');

        if (!$canManageLoans) {
            if ($loan->employee_id != $authUser->id) {
                abort(403, 'Unauthorized action.');
            }
            if (strtolower($loan->status) !== 'pending') {
                Flash::error('Approved or processed loan applications cannot be deleted.');
                return redirect()->route('employeeLoans.index');
            }
        } else {
            if ($loan->employee) {
                checkBranchAccess($loan->employee->branch_id);
            }
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
        $loan->next_payment_date = now()->addMonth();
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

        $dueDate = Carbon::now()->addMonth();

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

        Flash::success('Loan application rejected.');
        return redirect()->route('employeeLoans.show', $loan->id);
    }
}
