<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PfScheme;
use App\Models\ProvidentFundContribution;
use App\Models\ProvidentFundLoan;
use App\Models\PfWithdrawal;
use App\Models\PfSettlement;
use Illuminate\Support\Facades\Auth;

class PfDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name ?? '';

        if ($role === 'Super Admin') {
            return $this->adminDashboard($user, $role);
        } elseif ($role === 'HR' || $role === 'Admin') {
            return $this->branchDashboard($user);
        } else {
            return $this->employeeDashboard($user);
        }
    }

    private function adminDashboard($user, $role)
    {
        $totalMembers = \App\Models\User::where('is_pf_member', 1)->count();
        $totalEmployeeContrib = ProvidentFundContribution::where('status', 'Processed')->sum('employee_contribution');
        $totalEmployerContrib = ProvidentFundContribution::where('status', 'Processed')->sum('employer_contribution');
        $totalBalance = $totalEmployeeContrib + $totalEmployerContrib;

        $pendingLoans = ProvidentFundLoan::where('status', 'Pending')->count();
        $pendingWithdrawals = PfWithdrawal::where('status', 'Pending')->count();
        $pendingSettlements = PfSettlement::where('status', 'Pending')->count();

        return view('pf.dashboard.admin', compact(
            'totalMembers', 'totalBalance', 'pendingLoans', 'pendingWithdrawals', 'pendingSettlements', 'role'
        ));
    }

    private function branchDashboard($user)
    {
        $branchId = $user->branch_id;
        $totalMembers = \App\Models\User::where('is_pf_member', 1)->where('branch_id', $branchId)->count();
        
        $totalEmployeeContrib = ProvidentFundContribution::where('branch_id', $branchId)->where('status', 'Processed')->sum('employee_contribution');
        $totalEmployerContrib = ProvidentFundContribution::where('branch_id', $branchId)->where('status', 'Processed')->sum('employer_contribution');
        $totalBalance = $totalEmployeeContrib + $totalEmployerContrib;

        return view('pf.dashboard.branch', compact('totalMembers', 'totalBalance'));
    }

    private function employeeDashboard($user)
    {
        $contributions = ProvidentFundContribution::where('employee_id', $user->id)->where('status', 'Processed')->get();
        $employeeSum = $contributions->sum('employee_contribution');
        $employerSum = $contributions->sum('employer_contribution');
        $voluntarySum = $contributions->sum('voluntary_contribution');
        $profitSum = $contributions->sum('profit_amount');

        $totalBalance = $employeeSum + $employerSum + $voluntarySum + $profitSum;

        $outstandingLoan = ProvidentFundLoan::where('employee_id', $user->id)
                            ->whereIn('status', ['Approved', 'Disbursed'])
                            ->sum('outstanding_balance');

        $recentTransactions = \App\Models\PfLedger::where('employee_id', $user->id)
                                ->orderBy('created_at', 'desc')->take(10)->get();

        return view('pf.dashboard.employee', compact(
            'totalBalance', 'employeeSum', 'employerSum', 'voluntarySum', 'profitSum', 'outstandingLoan', 'recentTransactions'
        ));
    }
}
