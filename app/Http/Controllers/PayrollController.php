<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SalaryService;
use App\Models\Payroll;


class PayrollController extends Controller
{
    protected $salaryService;
    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function index(Request $request)
    {
        $branches = Branch::pluck('branch_name', 'id');
        $departments = Department::pluck('name', 'id');
        $designations = Designation::pluck('desi_name', 'id');

        $users = User::with(['branch', 'department', 'designation'])
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                return $query->where('department_id', $request->department_id);
            })
            ->when($request->filled('designation_id'), function ($query) use ($request) {
                return $query->where('designation_id', $request->designation_id);
            })
            ->get();

        return view('payroll.index', compact('users', 'branches', 'departments', 'designations'));
    }

    public function process(Request $request)
    {
        $salary_month = $request->input('salary_month');
        $userIds = $request->input('users');

        if (empty($userIds)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one user.']);
        }

        $result = $this->salaryService->salary_process($salary_month,  $userIds);

        if (empty($result['errors'])) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        } else {
            return response()->json(['success' => false, 'message' => $result['message'] . ": " . implode("; ", $result['errors'])]);
        }
    }


    public function salaryReport(Request $request)
    {
           $salary_reports = Payroll::select('payrolls.*', 'users.name')
            ->join('users', 'payrolls.user_id', '=', 'users.id','LEFT')
            ->whereIn('payrolls.user_id', $request->user_ids)
            ->where('payrolls.salary_month', date('Y-m-01', strtotime($request->salary_month)))
            ->get();
        $salary_month = $request->salary_month;
        // dd($salary_reports/);
        return view('payroll.salary_report', compact('salary_reports', 'salary_month'));
    }
    public function payslip(Request $request)
    {
        $salary_reports = Payroll::select('payrolls.*', 'users.name')
            ->join('users', 'payrolls.user_id', '=', 'users.id','LEFT')
            ->whereIn('payrolls.user_id', $request->user_ids)
            ->where('payrolls.salary_month', date('Y-m-01', strtotime($request->salary_month)))
            // ->groupBy('payrolls.id')
            ->get();
        // dd($salary_reports);
        $salary_month = $request->salary_month;
        return view('payroll.payslip', compact('salary_reports', 'salary_month'));
    }

}
