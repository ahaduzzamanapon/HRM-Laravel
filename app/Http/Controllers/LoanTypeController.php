<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LoanType;
use Flash;

class LoanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanTypes = LoanType::paginate(10);
        foreach ($loanTypes as $loanType) {
            if (is_string($loanType->loan_ceilings)) {
                $loanType->loan_ceilings = json_decode($loanType->loan_ceilings, true);
            }
        }
        return view('loan_types.index', compact('loanTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('loan_types.create');
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

        // Process loan_ceilings
        $loanCeilings = [];
        if ($request->has('loan_ceilings_grade') && $request->has('loan_ceilings_amount')) {
            $grades = $request->input('loan_ceilings_grade');
            $amounts = $request->input('loan_ceilings_amount');

            for ($i = 0; $i < count($grades); $i++) {
                if (!empty($grades[$i]) && !empty($amounts[$i])) {
                    $loanCeilings[] = [
                        'grade' => $grades[$i],
                        'amount' => $amounts[$i],
                    ];
                }
            }
        }
        $input['loan_ceilings'] = json_encode($loanCeilings);

        LoanType::create($input);
        Flash::success('Loan Type saved successfully.');
        return redirect(route('loanTypes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loanType = LoanType::find($id);
        if (empty($loanType)) {
            Flash::error('Loan Type not found');
            return redirect(route('loanTypes.index'));
        }
        return view('loan_types.show')->with('loanType', $loanType);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loanType = LoanType::find($id);
        if (empty($loanType)) {
            Flash::error('Loan Type not found');
            return redirect(route('loanTypes.index'));
        }

        // Ensure loan_ceilings is an array for the view
        if (is_string($loanType->loan_ceilings)) {
            $loanType->loan_ceilings = json_decode($loanType->loan_ceilings, true);
        }

        return view('loan_types.edit')->with('loanType', $loanType);
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
        $loanType = LoanType::find($id);
        if (empty($loanType)) {
            Flash::error('Loan Type not found');
            return redirect(route('loanTypes.index'));
        }

        $input = $request->all();

        // Process loan_ceilings
        $loanCeilings = [];
        if ($request->has('loan_ceilings_grade') && $request->has('loan_ceilings_amount')) {
            $grades = $request->input('loan_ceilings_grade');
            $amounts = $request->input('loan_ceilings_amount');

            for ($i = 0; $i < count($grades); $i++) {
                if (!empty($grades[$i]) && !empty($amounts[$i])) {
                    $loanCeilings[] = [
                        'grade' => $grades[$i],
                        'amount' => $amounts[$i],
                    ];
                }
            }
        }
        $input['loan_ceilings'] = json_encode($loanCeilings);

        $loanType->fill($input);
        $loanType->save();
        Flash::success('Loan Type updated successfully.');
        return redirect(route('loanTypes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loanType = LoanType::find($id);
        if (empty($loanType)) {
            Flash::error('Loan Type not found');
            return redirect(route('loanTypes.index'));
        }
        $loanType->delete();
        Flash::success('Loan Type deleted successfully.');
        return redirect(route('loanTypes.index'));
    }
}
