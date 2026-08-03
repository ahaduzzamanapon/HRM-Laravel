<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Department;
use Illuminate\Http\Request;
use Flash;
use Response;

class DepartmentController extends AppBaseController
{
    public function index(Request $request)
    {
        $departments = Department::paginate(10);
        return view('departments.index')->with('departments', $departments);
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(CreateDepartmentRequest $request)
    {
        Department::create($request->all());

        Flash::success('Department saved successfully.');
        return redirect(route('departments.index'));
    }

    public function show($id)
    {
        $department = Department::find($id);

        if (empty($department)) {
            Flash::error('Department not found');
            return redirect(route('departments.index'));
        }

        return view('departments.show')->with('department', $department);
    }

    public function edit($id)
    {
        $department = Department::find($id);

        if (empty($department)) {
            Flash::error('Department not found');
            return redirect(route('departments.index'));
        }

        return view('departments.edit')->with('department', $department);
    }

    public function update($id, UpdateDepartmentRequest $request)
    {
        $department = Department::find($id);

        if (empty($department)) {
            Flash::error('Department not found');
            return redirect(route('departments.index'));
        }

        $department->fill($request->all());
        $department->save();

        Flash::success('Department updated successfully.');
        return redirect(route('departments.index'));
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        if (empty($department)) {
            Flash::error('Department not found');
            return redirect(route('departments.index'));
        }

        $department->delete();

        Flash::success('Department deleted successfully.');
        return redirect(route('departments.index'));
    }
}

