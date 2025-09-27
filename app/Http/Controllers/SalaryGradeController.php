<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalaryGradeRequest;
use App\Http\Requests\UpdateSalaryGradeRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\SalaryGrade;
use Illuminate\Http\Request;
use Flash;
use Response;

class SalaryGradeController extends AppBaseController
{
    /**
     * Display a listing of the SalaryGrade.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var SalaryGrade $salaryGrades */
        $salaryGrades = SalaryGrade::paginate(10);

        return view('salary_grades.index')
            ->with('salaryGrades', $salaryGrades);
    }

    /**
     * Show the form for creating a new SalaryGrade.
     *
     * @return Response
     */
    public function create()
    {
        return view('salary_grades.create');
    }

    /**
     * Store a newly created SalaryGrade in storage.
     *
     * @param CreateSalaryGradeRequest $request
     *
     * @return Response
     */
    public function store(CreateSalaryGradeRequest $request)
    {
        $input = $request->all();

        /** @var SalaryGrade $salaryGrade */
        $salaryGrade = SalaryGrade::create($input);

        Flash::success('Salary Grade saved successfully.');

        return redirect(route('salaryGrades.index'));
    }

    /**
     * Display the specified SalaryGrade.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SalaryGrade $salaryGrade */
        $salaryGrade = SalaryGrade::find($id);

        if (empty($salaryGrade)) {
            Flash::error('Salary Grade not found');

            return redirect(route('salaryGrades.index'));
        }

        return view('salary_grades.show')->with('salaryGrade', $salaryGrade);
    }

    /**
     * Show the form for editing the specified SalaryGrade.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var SalaryGrade $salaryGrade */
        $salaryGrade = SalaryGrade::find($id);

        if (empty($salaryGrade)) {
            Flash::error('Salary Grade not found');

            return redirect(route('salaryGrades.index'));
        }

        return view('salary_grades.edit')->with('salaryGrade', $salaryGrade);
    }

    /**
     * Update the specified SalaryGrade in storage.
     *
     * @param int $id
     * @param UpdateSalaryGradeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSalaryGradeRequest $request)
    {
        /** @var SalaryGrade $salaryGrade */
        $salaryGrade = SalaryGrade::find($id);

        if (empty($salaryGrade)) {
            Flash::error('Salary Grade not found');

            return redirect(route('salaryGrades.index'));
        }

        $salaryGrade->fill($request->all());
        $salaryGrade->save();

        Flash::success('Salary Grade updated successfully.');

        return redirect(route('salaryGrades.index'));
    }

    /**
     * Remove the specified SalaryGrade from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SalaryGrade $salaryGrade */
        $salaryGrade = SalaryGrade::find($id);

        if (empty($salaryGrade)) {
            Flash::error('Salary Grade not found');

            return redirect(route('salaryGrades.index'));
        }

        $salaryGrade->delete();

        Flash::success('Salary Grade deleted successfully.');

        return redirect(route('salaryGrades.index'));
    }
}
