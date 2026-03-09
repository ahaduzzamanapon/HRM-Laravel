<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProvidentFundLoan;
use App\Models\User;
use Carbon\Carbon;
use Flash;

class ProvidentFundLoanController extends Controller
{
    public function index()
    {
        $loans = ProvidentFundLoan::with('employee')->orderBy('created_at', 'desc')->paginate(15);
        return view('provident_fund_loans.index', compact('loans'));
    }

    public function create()
    {
        $employees = User::where('is_pf_member', true)->get()->pluck('name', 'id');
        // Let's also include emp_id for better identification if available.
        $employees = User::where('is_pf_member', true)
            ->select('id', 'name', 'last_name', 'emp_id')
            ->get()
            ->mapWithKeys(function ($user) {
                $fullName = $user->name . ' ' . $user->last_name . ' (' . $user->emp_id . ')';
                return [$user->id => $fullName];
            });

        return view('provident_fund_loans.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'disbursement_date' => 'required|date',
        ]);

        $amount = $request->amount;
        $interestRate = $request->interest_rate;
        $installments = $request->installments;

        // Simple Interest Calculation: Total Interest = (Principal * Interest Rate * Time in Years)
        // If interest_rate is an annual percentage (e.g., 5 for 5%)
        $timeInYears = $installments / 12;
        $totalInterest = $amount * ($interestRate / 100) * $timeInYears;
        $totalAmount = $amount + $totalInterest;

        $monthlyInstallment = round($totalAmount / $installments, 2);

        $nextPaymentDate = Carbon::parse($request->disbursement_date)->addMonth()->format('Y-m-d');

        ProvidentFundLoan::create([
            'employee_id' => $request->employee_id,
            'amount' => $amount,
            'interest_rate' => $interestRate,
            'installments' => $installments,
            'monthly_installment' => $monthlyInstallment,
            'disbursement_date' => $request->disbursement_date,
            'next_payment_date' => $nextPaymentDate,
            'outstanding_balance' => $totalAmount,
            'status' => 'Disbursed',
            'remarks' => $request->remarks,
        ]);

        Flash::success('Provident Fund Loan granted successfully.');
        return redirect(route('providentFundLoans.index'));
    }

    public function show($id)
    {
        $loan = ProvidentFundLoan::with(['employee', 'repayments'])->findOrFail($id);
        return view('provident_fund_loans.show', compact('loan'));
    }
}
