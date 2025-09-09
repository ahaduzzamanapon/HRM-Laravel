<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LoanRepayment;
use App\Models\Loan;
use Flash;

class LoanRepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanRepayments = LoanRepayment::with('loan')->paginate(10);
        return view('loan_repayments.index', compact('loanRepayments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = Loan::all();
        return view('loan_repayments.create', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        LoanRepayment::create($input);
        Flash::success('Loan Repayment saved successfully.');
        return redirect(route('loanRepayments.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loanRepayment = LoanRepayment::with('loan')->find($id);
        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found');
            return redirect(route('loanRepayments.index'));
        }
        return view('loan_repayments.show')->with('loanRepayment', $loanRepayment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loanRepayment = LoanRepayment::find($id);
        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found');
            return redirect(route('loanRepayments.index'));
        }
        $loans = Loan::all();
        return view('loan_repayments.edit', compact('loanRepayment', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loanRepayment = LoanRepayment::find($id);
        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found');
            return redirect(route('loanRepayments.index'));
        }
        $loanRepayment->fill($request->all());
        $loanRepayment->save();
        Flash::success('Loan Repayment updated successfully.');
        return redirect(route('loanRepayments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loanRepayment = LoanRepayment::find($id);
        if (empty($loanRepayment)) {
            Flash::error('Loan Repayment not found');
            return redirect(route('loanRepayments.index'));
        }
        $loanRepayment->delete();
        Flash::success('Loan Repayment deleted successfully.');
        return redirect(route('loanRepayments.index'));
    }
}
