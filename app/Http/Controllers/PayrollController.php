<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SalaryService;
use App\Models\Payroll;
use App\Models\SiteSetting;

class PayrollController extends Controller
{
    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function index(Request $request)
    {
        $authUser = \Illuminate\Support\Facades\Auth::user();
        if (\App\Services\AuthorizationEngine::isEmployeeRole($authUser)) {
            return redirect()->route('my-payroll.index');
        }

        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        $departments = Department::pluck('name', 'id');
        $designations = Designation::pluck('desi_name', 'id');

        $usersQuery = User::where('group_id', '!=', 1)->with(['branch', 'department', 'designation'])
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                return $query->where('department_id', $request->department_id);
            })
            ->when($request->filled('designation_id'), function ($query) use ($request) {
                return $query->where('designation_id', $request->designation_id);
            });

        applyBranchScope($usersQuery, 'branch_id');
        $users = $usersQuery->get();

        return view('payroll.index', compact('users', 'branches', 'departments', 'designations'));
    }

    public function process(Request $request)
    {
        $salary_month = $request->input('salary_month');
        $userIds = $request->input('users');

        if (empty($userIds)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one user.']);
        }

        if (!isSuperAdmin()) {
            $allowedUserIds = User::whereIn('id', $userIds)->where('branch_id', userBranchId())->pluck('id')->toArray();
            $userIds = $allowedUserIds;
            if (empty($userIds)) {
                return response()->json(['success' => false, 'message' => 'Selected users are outside your assigned branch.']);
            }
        }

        $result = $this->salaryService->salary_process($salary_month, $userIds);

        if (empty($result['errors'])) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        } else {
            return response()->json(['success' => false, 'message' => $result['message'] . ": " . implode("; ", $result['errors'])]);
        }
    }

    public function salaryReport(Request $request)
    {
        $query = Payroll::select('payrolls.*', 'users.emp_id as emp_id', 'users.name', 'users.last_name', 'users.basic_salary', 'users.account_no', 'users.emp_type', 'designations.desi_name', 'salary_grades.*', 'banksetups.*')
            ->join('users', 'payrolls.user_id', '=', 'users.id', 'LEFT')
            ->join('designations', 'users.designation_id', '=', 'designations.id', 'LEFT')
            ->join('salary_grades', 'users.salary_grade_id', '=', 'salary_grades.id', 'LEFT')
            ->join('banksetups', 'users.bank_id', '=', 'banksetups.id', 'LEFT')
            ->whereIn('payrolls.user_id', (array)$request->user_ids)
            ->where('payrolls.salary_month', date('Y-m-01', strtotime($request->salary_month)));

        applyBranchScope($query, 'users.branch_id');
        $salary_reports = $query->get();

        $salary_month = $request->salary_month;
        $siteSetting = SiteSetting::first();
        return view('payroll.salary_report', compact('salary_reports', 'salary_month', 'siteSetting'));
    }

    public function payslip(Request $request)
    {
        $query = Payroll::select('payrolls.*', 'users.emp_id as emp_id', 'users.name', 'users.last_name', 'users.basic_salary', 'users.account_no', 'users.emp_type', 'designations.desi_name', 'salary_grades.*', 'banksetups.*')
            ->join('users', 'payrolls.user_id', '=', 'users.id', 'LEFT')
            ->join('designations', 'users.designation_id', '=', 'designations.id', 'LEFT')
            ->join('salary_grades', 'users.salary_grade_id', '=', 'salary_grades.id', 'LEFT')
            ->join('banksetups', 'users.bank_id', '=', 'banksetups.id', 'LEFT')
            ->whereIn('payrolls.user_id', (array)$request->user_ids)
            ->where('payrolls.salary_month', date('Y-m-01', strtotime($request->salary_month)));

        applyBranchScope($query, 'users.branch_id');
        $salary_reports = $query->get();

        $salary_month = $request->salary_month;
        $siteSetting = SiteSetting::first();
        return view('payroll.payslip', compact('salary_reports', 'salary_month', 'siteSetting'));
    }

    public function tax(Request $request)
    {
        $selectedMonth = date('Y-m-01', strtotime($request->salary_month));
        $startDate = date('Y-m-01', strtotime('-11 months', strtotime($selectedMonth)));
        $query = Payroll::select(
            'payrolls.*',
            'users.emp_id as emp_id',
            'users.name',
            'users.last_name',
            'users.basic_salary',
            'users.account_no',
            'users.emp_type',
            'salary_grades.*',
            'banksetups.*',
            'designations.desi_name',
            'departments.name as dept_name'
        )
            ->join('users', 'payrolls.user_id', '=', 'users.id', 'LEFT')
            ->join('designations', 'users.designation_id', '=', 'designations.id', 'LEFT')
            ->join('departments', 'users.department_id', '=', 'departments.id', 'LEFT')
            ->join('salary_grades', 'users.salary_grade_id', '=', 'salary_grades.id', 'LEFT')
            ->join('banksetups', 'users.bank_id', '=', 'banksetups.id', 'LEFT')
            ->whereIn('payrolls.user_id', (array)$request->user_ids)
            ->where('payrolls.tax_deduct', '>', 0)
            ->where('payrolls.salary_month', $selectedMonth)
            ->orderBy('payrolls.salary_month', 'asc');

        applyBranchScope($query, 'users.branch_id');
        $salary_reports = $query->get();

        $salary_month = $request->salary_month;
        $siteSetting = SiteSetting::first();
        return view('payroll.tax', compact('salary_reports', 'salary_month', 'siteSetting'));
    }
}
