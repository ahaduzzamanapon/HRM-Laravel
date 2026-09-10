<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Branch;
use Illuminate\Http\Request;
use Flash;

class DesignationController extends AppBaseController
{
    public function index(Request $request)
    {
        $designations = Designation::orderBy('created_at', 'desc')->paginate(15);
        return view('designations.index', compact('designations'));
    }

    public function create()
    {
        return view('designations.create');
    }

    public function store(CreateDesignationRequest $request)
    {
        $input = $request->all();
        Designation::create($input);

        Flash::success('Designation saved successfully.');
        return redirect(route('designations.index'));
    }

    public function show($id)
    {
        $designation = Designation::find($id);

        if (empty($designation)) {
            Flash::error('Designation not found');
            return redirect(route('designations.index'));
        }

        return view('designations.show')->with('designation', $designation);
    }

    public function edit($id)
    {
        $designation = Designation::find($id);

        if (empty($designation)) {
            Flash::error('Designation not found');
            return redirect(route('designations.index'));
        }

        return view('designations.edit', compact('designation'));
    }

    public function update($id, UpdateDesignationRequest $request)
    {
        $designation = Designation::find($id);

        if (empty($designation)) {
            Flash::error('Designation not found');
            return redirect(route('designations.index'));
        }

        $input = $request->all();
        $designation->fill($input);
        $designation->save();

        Flash::success('Designation updated successfully.');
        return redirect(route('designations.index'));
    }

    public function destroy($id)
    {
        $designation = Designation::find($id);

        if (empty($designation)) {
            Flash::error('Designation not found');
            return redirect(route('designations.index'));
        }

        $designation->delete();

        Flash::success('Designation deleted successfully.');
        return redirect(route('designations.index'));
    }

    public function getDepartmentsByBranch($branchId)
    {
        $query = Department::query();
        if ($branchId && $branchId !== 'all') {
            $query->where(function($q) use ($branchId) {
                $q->where('branch_id', $branchId)->orWhereNull('branch_id');
            });
        } else {
            applyBranchScope($query, 'branch_id');
        }

        $departments = $query->pluck('name', 'id');
        return response()->json($departments);
    }
}
