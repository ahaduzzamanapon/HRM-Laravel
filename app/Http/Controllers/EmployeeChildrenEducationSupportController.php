<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmployeeChildrenEducationSupport;
use App\Models\User;
use Flash;

class EmployeeChildrenEducationSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeChildrenEducationSupports = EmployeeChildrenEducationSupport::with('employee')->paginate(10);
        return view('employee_children_education_supports.index', compact('employeeChildrenEducationSupports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('employee_children_education_supports.create', compact('users'));
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
        EmployeeChildrenEducationSupport::create($input);
        Flash::success('Employee Children Education Support saved successfully.');
        return redirect(route('employeeChildrenEducationSupports.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::with('employee')->find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }
        return view('employee_children_education_supports.show')->with('employeeChildrenEducationSupport', $employeeChildrenEducationSupport);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }
        $users = User::all();
        return view('employee_children_education_supports.edit', compact('employeeChildrenEducationSupport', 'users'));
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
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }
        $employeeChildrenEducationSupport->fill($request->all());
        $employeeChildrenEducationSupport->save();
        Flash::success('Employee Children Education Support updated successfully.');
        return redirect(route('employeeChildrenEducationSupports.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeChildrenEducationSupport = EmployeeChildrenEducationSupport::find($id);
        if (empty($employeeChildrenEducationSupport)) {
            Flash::error('Employee Children Education Support not found');
            return redirect(route('employeeChildrenEducationSupports.index'));
        }
        $employeeChildrenEducationSupport->delete();
        Flash::success('Employee Children Education Support deleted successfully.');
        return redirect(route('employeeChildrenEducationSupports.index'));
    }
}
