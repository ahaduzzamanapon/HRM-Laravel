<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Branch;
use App\Models\SalaryGrade;
use App\Models\TaxSetup;
use App\Models\LeaveApplication;
use App\Models\Loan;
use App\Models\ChildAllowance;
use App\Models\ProvidentFundContribution;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::count();
        $totalDepartments = Department::count();
        $totalBranches = Branch::count();
        $newEmployees = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $totalSalaryGrades = SalaryGrade::count();
        $totalTaxSetups = TaxSetup::count();
        $totalLeaveApplications = LeaveApplication::count();
        $pendingLeaveApplications = LeaveApplication::where('status', 'pending')->count();
        $totalLoans = Loan::count();
        $pendingLoans = Loan::where('status', 'pending')->count();
        $totalChildren = ChildAllowance::count();
        $totalProvidentFund = ProvidentFundContribution::sum('employee_contribution') + ProvidentFundContribution::sum('employer_contribution');

        $employeeJoinData = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(7))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = $employeeJoinData->pluck('month');
        $data = $employeeJoinData->pluck('count');

        return view('index', compact('totalEmployees', 'totalDepartments', 'totalBranches', 'newEmployees', 'totalSalaryGrades', 'totalTaxSetups', 'totalLeaveApplications', 'pendingLeaveApplications', 'totalLoans', 'pendingLoans', 'totalChildren', 'totalProvidentFund', 'labels', 'data'));
    }
}
