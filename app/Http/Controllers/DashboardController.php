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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $roleName = strtolower(optional($authUser->role)->name ?? optional($authUser->role)->role_name ?? '');
        $isAdmin  = isSuperAdmin() 
            || $authUser->group_id == 1 
            || $authUser->group_id == 2 
            || in_array($roleName, ['admin', 'super admin', 'superadmin', 'hr manager', 'branch admin'])
            || (can('staff_management') && (can('manage_site_settings') || can('manage_roles_and_permissions') || can('manage_branches')));

        if ($isAdmin) {
            $branches = Branch::all();

            // Determine active branch filter (default to All Branches for Super Admin)
            if (isSuperAdmin()) {
                if ($request->has('branch_id')) {
                    $branchInput = $request->get('branch_id');
                    $selectedBranchId = ($branchInput === '' || $branchInput === 'all' || $branchInput === null) ? null : $branchInput;
                    session(['dashboard_branch_id' => $selectedBranchId]);
                } else {
                    $selectedBranchId = session('dashboard_branch_id');
                    if ($selectedBranchId === 'all') {
                        $selectedBranchId = null;
                    }
                }
            } else {
                $selectedBranchId = userBranchId();
            }

            // Employee query (branch-aware)
            $empQuery = User::where('group_id', '!=', 1);
            if ($selectedBranchId) {
                $empQuery->where('branch_id', $selectedBranchId);
            } else {
                applyBranchScope($empQuery, 'branch_id');
            }

            $totalEmployees   = (clone $empQuery)->count();
            $newEmployees     = (clone $empQuery)->where('created_at', '>=', Carbon::now()->subDays(30))->count();

            // Department count (branch-wise)
            $deptQuery = Department::query();
            if ($selectedBranchId) {
                $deptQuery->where(function($q) use ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId)
                      ->orWhereNull('branch_id');
                });
            } else {
                applyBranchScope($deptQuery, 'branch_id');
            }
            $totalDepartments = $deptQuery->count();

            // Designation count (branch & department wise)
            $desigQuery = \App\Models\Designation::query();
            if ($selectedBranchId) {
                $desigQuery->where(function($q) use ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId)
                      ->orWhereNull('branch_id');
                });
            } else {
                applyBranchScope($desigQuery, 'branch_id');
            }
            $totalDesignations = $desigQuery->count();

            // Branch count: super admin sees all branches or 1 if filtered
            if ($selectedBranchId) {
                $totalBranches = 1;
            } else {
                $branchesQuery = Branch::query();
                applyBranchScope($branchesQuery, 'id');
                $totalBranches = $branchesQuery->count();
            }

            $totalSalaryGrades = SalaryGrade::count();
            $totalTaxSetups    = TaxSetup::count();

            // Leave stats (branch-scoped)
            $leaveBase = LeaveApplication::query();
            if ($selectedBranchId) {
                $leaveBase->whereHas('user', function($q) use ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId);
                });
            } else {
                applyUserBranchScope($leaveBase, 'user');
            }

            $totalLeaveApplications    = (clone $leaveBase)->count();
            $pendingLeaveApplications  = (clone $leaveBase)->where('status', 'Pending')->count();
            $approvedLeaveApplications = (clone $leaveBase)->whereIn('status', ['Approved', 'First Level Approved'])->count();
            $rejectedLeaveApplications = (clone $leaveBase)->where('status', 'Rejected')->count();

            // Loan stats (branch-scoped)
            $loanBase = Loan::query();
            if ($selectedBranchId) {
                $loanBase->whereHas('employee', function($q) use ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId);
                });
            } else {
                applyUserBranchScope($loanBase, 'employee');
            }

            $totalLoans   = (clone $loanBase)->count();
            $pendingLoans = (clone $loanBase)->where('status', 'pending')->count();

            // Allowance & Provident Fund (branch-scoped)
            $childBase = ChildAllowance::query();
            if ($selectedBranchId) {
                $childBase->whereHas('user', function($q) use ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId);
                });
            } else {
                applyUserBranchScope($childBase, 'user');
            }
            $totalChildren = $childBase->count();

            $pfBase = ProvidentFundContribution::query();
            if ($selectedBranchId) {
                $pfBase->whereHas('employee', function($q) use ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId);
                });
            } else {
                applyUserBranchScope($pfBase, 'employee');
            }

            $totalProvidentFund = (clone $pfBase)->sum('employee_contribution')
                                + (clone $pfBase)->sum('employer_contribution');

            // Employee join chart (branch-scoped)
            $joinQuery = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('created_at', '>=', Carbon::now()->subMonths(7))
                ->where('group_id', '!=', 1);
            if ($selectedBranchId) {
                $joinQuery->where('branch_id', $selectedBranchId);
            } else {
                applyBranchScope($joinQuery, 'branch_id');
            }

            $employeeJoinData = $joinQuery->groupBy('month')->orderBy('month', 'asc')->get();

            $labels = $employeeJoinData->pluck('month');
            $data   = $employeeJoinData->pluck('count');

            // Get active branch details if filtered
            $activeBranch = $selectedBranchId ? Branch::find($selectedBranchId) : null;

            return view('index', compact(
                'branches', 'selectedBranchId', 'activeBranch',
                'totalEmployees', 'totalDepartments', 'totalBranches', 'newEmployees',
                'totalSalaryGrades', 'totalTaxSetups',
                'totalLeaveApplications', 'pendingLeaveApplications',
                'approvedLeaveApplications', 'rejectedLeaveApplications',
                'totalLoans', 'pendingLoans',
                'totalChildren', 'totalProvidentFund',
                'labels', 'data'
            ));
        } else {
            $user = $authUser;

            $totalLeaveApplications    = LeaveApplication::where('user_id', $user->id)->count();
            $pendingLeaveApplications  = LeaveApplication::where('user_id', $user->id)->where('status', 'Pending')->count();
            $approvedLeaveApplications = LeaveApplication::where('user_id', $user->id)->whereIn('status', ['Approved', 'First Level Approved'])->count();
            $rejectedLeaveApplications = LeaveApplication::where('user_id', $user->id)->where('status', 'Rejected')->count();

            $totalLoans   = Loan::where('employee_id', $user->id)->count();
            $pendingLoans = Loan::where('employee_id', $user->id)->where('status', 'pending')->count();

            $mySalaryGrade    = optional($user->salaryGrade)->grade ?? 'N/A';
            $myProvidentFund  = ProvidentFundContribution::where('employee_id', $user->id)->sum('employee_contribution')
                              + ProvidentFundContribution::where('employee_id', $user->id)->sum('employer_contribution');
            $myChildren       = ChildAllowance::where('user_id', $user->id)->count();

            return view('employee_dashboard', compact(
                'totalLeaveApplications', 'pendingLeaveApplications',
                'approvedLeaveApplications', 'rejectedLeaveApplications',
                'totalLoans', 'pendingLoans',
                'mySalaryGrade', 'myProvidentFund', 'myChildren'
            ));
        }
    }
}
