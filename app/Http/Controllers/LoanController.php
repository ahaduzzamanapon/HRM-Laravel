<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Loan;
use App\Models\User;
use App\Models\LoanType;
use Flash;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::with(['employee', 'loanType'])->paginate(10);
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $loanTypes = LoanType::all();
        return view('loans.create', compact('users', 'loanTypes'));
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
        Loan::create($input);
        Flash::success('Loan saved successfully.');
        return redirect(route('loans.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::with(['employee', 'loanType', 'loanRepayments'])->find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }
        return view('loans.show')->with('loan', $loan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan = Loan::find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }
        $users = User::all();
        $loanTypes = LoanType::all();
        return view('loans.edit', compact('loan', 'users', 'loanTypes'));
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
        $loan = Loan::find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }
        $loan->fill($request->all());
        $loan->save();
        Flash::success('Loan updated successfully.');
        return redirect(route('loans.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);
        if (empty($loan)) {
            Flash::error('Loan not found');
            return redirect(route('loans.index'));
        }
        $loan->delete();
        Flash::success('Loan deleted successfully.');
        return redirect(route('loans.index'));
    }
}
