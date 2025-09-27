<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaxSetupRequest;
use App\Http\Requests\UpdateTaxSetupRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\TaxSetup;
use Illuminate\Http\Request;
use Flash;
use Response;

class TaxSetupController extends AppBaseController
{
    /**
     * Display a listing of the TaxSetup.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var TaxSetup $taxSetups */
        $taxSetups = TaxSetup::paginate(10);

        return view('tax_setups.index')
            ->with('taxSetups', $taxSetups);
    }

    /**
     * Show the form for creating a new TaxSetup.
     *
     * @return Response
     */
    public function create()
    {
        return view('tax_setups.create');
    }

    /**
     * Store a newly created TaxSetup in storage.
     *
     * @param CreateTaxSetupRequest $request
     *
     * @return Response
     */
    public function store(CreateTaxSetupRequest $request)
    {
        $input = $request->all();

        /** @var TaxSetup $taxSetup */
        $taxSetup = TaxSetup::create($input);

        Flash::success('Tax Setup saved successfully.');

        return redirect(route('taxSetups.index'));
    }

    /**
     * Display the specified TaxSetup.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TaxSetup $taxSetup */
        $taxSetup = TaxSetup::find($id);

        if (empty($taxSetup)) {
            Flash::error('Tax Setup not found');

            return redirect(route('taxSetups.index'));
        }

        return view('tax_setups.show')->with('taxSetup', $taxSetup);
    }

    /**
     * Show the form for editing the specified TaxSetup.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var TaxSetup $taxSetup */
        $taxSetup = TaxSetup::find($id);

        if (empty($taxSetup)) {
            Flash::error('Tax Setup not found');

            return redirect(route('taxSetups.index'));
        }

        return view('tax_setups.edit')->with('taxSetup', $taxSetup);
    }

    /**
     * Update the specified TaxSetup in storage.
     *
     * @param int $id
     * @param UpdateTaxSetupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTaxSetupRequest $request)
    {
        /** @var TaxSetup $taxSetup */
        $taxSetup = TaxSetup::find($id);

        if (empty($taxSetup)) {
            Flash::error('Tax Setup not found');

            return redirect(route('taxSetups.index'));
        }

        $taxSetup->fill($request->all());
        $taxSetup->save();

        Flash::success('Tax Setup updated successfully.');

        return redirect(route('taxSetups.index'));
    }

    /**
     * Remove the specified TaxSetup from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TaxSetup $taxSetup */
        $taxSetup = TaxSetup::find($id);

        if (empty($taxSetup)) {
            Flash::error('Tax Setup not found');

            return redirect(route('taxSetups.index'));
        }

        $taxSetup->delete();

        Flash::success('Tax Setup deleted successfully.');

        return redirect(route('taxSetups.index'));
    }
}
