<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PfLedger;

class PfReportController extends Controller
{
    public function yearly(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $user = \Illuminate\Support\Facades\Auth::user();
        $interestsQuery = \App\Models\PfYearlyInterest::with('employee')
            ->where('year', $year);

        if ($user->role && $user->role->name !== 'Super Admin') {
            $interestsQuery->whereHas('employee', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
            
        $interests = $interestsQuery->get();
            
        return view('pf.reports.yearly', compact('interests', 'year'));
    }

    public function calculateInterest(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric',
            'interest_rate' => 'required|numeric'
        ]);

        $year = $request->year;
        $rate = $request->interest_rate / 100;

        $user = \Illuminate\Support\Facades\Auth::user();
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
        // For individual PF Statement Report
        return view('pf.reports.statement');
    }

    public function analytics(Request $request)
    {
        return view('pf.reports.analytics');
    }
}
