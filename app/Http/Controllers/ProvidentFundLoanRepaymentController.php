<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProvidentFundLoanRepayment;
use App\Models\ProvidentFundLoan;
use Carbon\Carbon;
use Flash;

class ProvidentFundLoanRepaymentController extends Controller
{
    public function index()
    {
        $repayments = ProvidentFundLoanRepayment::with('loan.employee')->orderBy('created_at', 'desc')->paginate(15);
        return view('provident_fund_loan_repayments.index', compact('repayments'));
    }

    public function create(Request $request)
    {
        $loanId = $request->query('loan_id', null);

        $activeLoansQuery = ProvidentFundLoan::with('employee')->whereIn('status', ['Approved', 'Disbursed']);

        if ($loanId) {
            $activeLoansQuery->where('id', $loanId);
        }

        $activeLoans = $activeLoansQuery->get()
            ->mapWithKeys(function ($loan) {
                $label = $loan->employee->name . ' ' . $loan->employee->last_name . ' (' . $loan->employee->emp_id . ') - Bal: ' . number_format($loan->outstanding_balance, 2);
                return [$loan->id => $label];
            });

        return view('provident_fund_loan_repayments.create', compact('activeLoans', 'loanId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provident_fund_loan_id' => 'required|exists:provident_fund_loans,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'required|date',
        ]);

        $loan = ProvidentFundLoan::findOrFail($request->provident_fund_loan_id);
        $amountPaid = $request->amount;

        // Prevent overpaying
        if ($amountPaid > $loan->outstanding_balance) {
            Flash::error('Repayment amount cannot exceed the outstanding balance (' . number_format($loan->outstanding_balance, 2) . ').');
            return redirect()->back()->withInput();
        }

        // Add Repayment Record
        ProvidentFundLoanRepayment::create([
            'provident_fund_loan_id' => $loan->id,
            'amount' => $amountPaid,
            'repayment_date' => $request->repayment_date,
            'remarks' => $request->remarks,
        ]);

        // Update Loan Database
        $loan->outstanding_balance -= $amountPaid;

        if ($loan->outstanding_balance <= 0) {
            $loan->status = 'Repaid';
            $loan->next_payment_date = null; // No more payments needed
        } else {
            // Push the next payment date forward by one month
            $loan->next_payment_date = Carbon::parse($loan->next_payment_date)->addMonth()->format('Y-m-d');
        }

        $loan->save();

        Flash::success('Repayment recorded successfully.');
        return redirect(route('providentFundLoans.show', $loan->id));
    }
}
