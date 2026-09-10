<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Department;
use App\Models\Branch;
use Illuminate\Http\Request;
use Flash;

class DepartmentController extends AppBaseController
{
    public function index(Request $request)
    {
        $departments = Department::orderBy('created_at', 'desc')->paginate(15);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(CreateDepartmentRequest $request)
    {
        $input = $request->all();
        Department::create($input);

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

        return view('departments.edit', compact('department'));
    }

    public function update($id, UpdateDepartmentRequest $request)
    {
        $department = Department::find($id);

        if (empty($department)) {
            Flash::error('Department not found');
            return redirect(route('departments.index'));
        }

        $input = $request->all();
        $department->fill($input);
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
