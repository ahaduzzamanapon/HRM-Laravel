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
    /**
     * Display a listing of the Designation.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Designation $designations */
        $designations = Designation::paginate(10);

        return view('designations.index')
            ->with('designations', $designations);
    }

    /**
     * Show the form for creating a new Designation.
     *
     * @return Response
     */
    public function create()
    {
        return view('designations.create');
    }

    /**
     * Store a newly created Designation in storage.
     *
     * @param CreateDesignationRequest $request
     *
     * @return Response
     */
    public function store(CreateDesignationRequest $request)
    {
        $input = $request->all();

        /** @var Designation $designation */
        $designation = Designation::create($input);

        Flash::success('Designation saved successfully.');

        return redirect(route('designations.index'));
    }

    /**
     * Display the specified Designation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Designation $designation */
        $designation = Designation::find($id);

        if (empty($designation)) {
            Flash::error('Designation not found');

            return redirect(route('designations.index'));
        }

        return view('designations.show')->with('designation', $designation);
    }

    /**
     * Show the form for editing the specified Designation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Designation $designation */
        $designation = Designation::find($id);

        if (empty($designation)) {
            Flash::error('Designation not found');

            return redirect(route('designations.index'));
        }

        return view('designations.edit')->with('designation', $designation);
    }

    /**
     * Update the specified Designation in storage.
     *
     * @param int $id
     * @param UpdateDesignationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDesignationRequest $request)
    {
        /** @var Designation $designation */
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

    /**
     * Remove the specified Designation from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Designation $designation */
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
