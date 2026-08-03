<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Designation;
use Illuminate\Http\Request;
use Flash;
use Response;

class DesignationController extends AppBaseController
{
    public function index(Request $request)
    {
        // Designations are shared/global config — Super Admin manages all, others see all but can't pollute
        $designations = Designation::paginate(10);
        return view('designations.index')->with('designations', $designations);
    }

    public function create()
    {
        return view('designations.create');
    }

    public function store(CreateDesignationRequest $request)
    {
        Designation::create($request->all());
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
        return view('designations.edit')->with('designation', $designation);
    }

    public function update($id, UpdateDesignationRequest $request)
    {
        $designation = Designation::find($id);
        if (empty($designation)) {
            Flash::error('Designation not found');
            return redirect(route('designations.index'));
        }
        $designation->fill($request->all());
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
}
