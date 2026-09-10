<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PfLedger;
use App\Models\ProvidentFundContribution;
use App\Models\ProvidentFundLoan;
use App\Models\PfWithdrawal;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\Auth;

class PfReportController extends Controller
{
    public function yearly(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $user = Auth::user();
        $interestsQuery = \App\Models\PfYearlyInterest::with('employee')
            ->where('year', $year);

        if (AuthorizationEngine::isEmployeeRole($user)) {
            $interestsQuery->where('employee_id', $user->id);
        } elseif ($user->role && $user->role->name !== 'Super Admin') {
            $interestsQuery->whereHas('employee', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
            
        $interests = $interestsQuery->get();
            
        return view('pf.reports.yearly', compact('interests', 'year'));
    }

    public function calculateInterest(Request $request)
    {
        $user = Auth::user();
        if (AuthorizationEngine::isEmployeeRole($user)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'year' => 'required|numeric',
            'interest_rate' => 'required|numeric'
        ]);

        $year = $request->year;
        $rate = $request->interest_rate / 100;

        $employeesQuery = \App\Models\User::where('is_pf_member', 1)->where('status', '!=', 'admin');

        if ($user->role && $user->role->name !== 'Super Admin') {
            $employeesQuery->where('branch_id', $user->branch_id);
        }

        $employees = $employeesQuery->get();
        foreach ($employees as $employee) {
            $balance = $employee->provident_fund_balance ?? 0;
            $interest = $balance * $rate;

            \App\Models\PfYearlyInterest::updateOrCreate(
                ['employee_id' => $employee->id, 'year' => $year],
                [
                    'balance_before' => $balance,
                    'interest_amount' => $interest,
                    'balance_after' => $balance + $interest
                ]
            );

            // Update user's total balance
            $employee->provident_fund_balance += $interest;
            $employee->save();
        }

        return back()->with('success', 'Yearly interest calculated successfully.');
    }

    public function statement(Request $request)
    {
        $user = Auth::user();
        $isEmployee = AuthorizationEngine::isEmployeeRole($user);

        if ($isEmployee) {
            $employee = $user;
        } else {
            $employeeId = $request->input('employee_id', $user->id);
            $employee = \App\Models\User::find($employeeId) ?: $user;
        }

        $contributions = ProvidentFundContribution::where('employee_id', $employee->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $employeesQuery = \App\Models\User::where('is_pf_member', 1);
        if ($user->role && $user->role->name !== 'Super Admin') {
            $employeesQuery->where('branch_id', $user->branch_id);
        }
        $employees = $employeesQuery->get();

        return view('pf.reports.statement', compact('employee', 'contributions', 'employees', 'isEmployee'));
    }

    public function analytics(Request $request)
    {
        $user = Auth::user();
        $isEmployee = AuthorizationEngine::isEmployeeRole($user);

        if ($isEmployee) {
            $totalFund = ProvidentFundContribution::where('employee_id', $user->id)
                ->selectRaw('SUM(employee_contribution + employer_contribution + voluntary_contribution + profit_amount) as total')
                ->value('total') ?? 0;

            $monthlyContributions = ProvidentFundContribution::where('employee_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->value('employee_contribution') ?? 0;

            $activeLoans = ProvidentFundLoan::where('employee_id', $user->id)
                ->whereIn('status', ['Approved', 'Disbursed'])
                ->count();

            $pendingWithdrawals = PfWithdrawal::where('employee_id', $user->id)
                ->where('status', 'Pending')
                ->count();

            $recentTransactions = PfLedger::with(['employee'])
                ->where('employee_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } else {
            $queryContrib = ProvidentFundContribution::query();
            $queryLoan = ProvidentFundLoan::whereIn('status', ['Approved', 'Disbursed']);
            $queryWithdrawal = PfWithdrawal::where('status', 'Pending');
            $queryLedger = PfLedger::with(['employee']);

            if ($user->role && $user->role->name !== 'Super Admin') {
                $branchId = $user->branch_id;
                $queryContrib->where('branch_id', $branchId);
                $queryLoan->where('branch_id', $branchId);
                $queryWithdrawal->where('branch_id', $branchId);
                $queryLedger->where('branch_id', $branchId);
            }

            $totalFund = (clone $queryContrib)->selectRaw('SUM(employee_contribution + employer_contribution + voluntary_contribution + profit_amount) as total')->value('total') ?? 0;
            $monthlyContributions = (clone $queryContrib)->whereMonth('created_at', now()->month)->sum('employee_contribution');
            $activeLoans = $queryLoan->count();
            $pendingWithdrawals = $queryWithdrawal->count();
            $recentTransactions = $queryLedger->orderBy('created_at', 'desc')->take(10)->get();
        }

        return view('pf.reports.analytics', compact(
            'totalFund', 'monthlyContributions', 'activeLoans', 'pendingWithdrawals', 'recentTransactions', 'isEmployee'
        ));
    }

    public function ledger(Request $request)
    {
        $user = Auth::user();
        $query = PfLedger::with(['employee', 'branch'])->orderBy('created_at', 'desc');

        if (AuthorizationEngine::isEmployeeRole($user)) {
            $query->where('employee_id', $user->id);
        } elseif ($user->role && $user->role->name !== 'Super Admin') {
            $query->where(function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id)
                  ->orWhereHas('employee', function ($eq) use ($user) {
                      $eq->where('branch_id', $user->branch_id);
                  });
            });
        }

        if ($request->filled('employee_id') && !AuthorizationEngine::isEmployeeRole($user)) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('branch_id') && !AuthorizationEngine::isEmployeeRole($user)) {
            $query->where('branch_id', $request->branch_id);
        }

        $ledgers = $query->paginate(20);
        return view('pf.reports.ledger', compact('ledgers'));
    }
}
