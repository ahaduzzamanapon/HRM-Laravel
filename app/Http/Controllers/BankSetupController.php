<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBankSetupRequest;
use App\Http\Requests\UpdateBankSetupRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\BankSetup;
use Illuminate\Http\Request;
use Flash;
use Response;

class BankSetupController extends AppBaseController
{
    /**
     * Display a listing of the BankSetup.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var BankSetup $bankSetups */
        $bankSetups = BankSetup::paginate(10);

        return view('bank_setups.index')
            ->with('bankSetups', $bankSetups);
    }

    /**
     * Show the form for creating a new BankSetup.
     *
     * @return Response
     */
    public function create()
    {
        return view('bank_setups.create');
    }

    /**
     * Store a newly created BankSetup in storage.
     *
     * @param CreateBankSetupRequest $request
     *
     * @return Response
     */
    public function store(CreateBankSetupRequest $request)
    {
        $input = $request->all();

        /** @var BankSetup $bankSetup */
        $bankSetup = BankSetup::create($input);

        Flash::success('Bank Setup saved successfully.');

        return redirect(route('bankSetups.index'));
    }

    /**
     * Display the specified BankSetup.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BankSetup $bankSetup */
        $bankSetup = BankSetup::find($id);

        if (empty($bankSetup)) {
            Flash::error('Bank Setup not found');

            return redirect(route('bankSetups.index'));
        }

        return view('bank_setups.show')->with('bankSetup', $bankSetup);
    }

    /**
     * Show the form for editing the specified BankSetup.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var BankSetup $bankSetup */
        $bankSetup = BankSetup::find($id);

        if (empty($bankSetup)) {
            Flash::error('Bank Setup not found');

            return redirect(route('bankSetups.index'));
        }

        return view('bank_setups.edit')->with('bankSetup', $bankSetup);
    }

    /**
     * Update the specified BankSetup in storage.
     *
     * @param int $id
     * @param UpdateBankSetupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBankSetupRequest $request)
    {
        /** @var BankSetup $bankSetup */
        $bankSetup = BankSetup::find($id);

        if (empty($bankSetup)) {
            Flash::error('Bank Setup not found');

            return redirect(route('bankSetups.index'));
        }

        $bankSetup->fill($request->all());
        $bankSetup->save();

        Flash::success('Bank Setup updated successfully.');

        return redirect(route('bankSetups.index'));
    }

    /**
     * Remove the specified BankSetup from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BankSetup $bankSetup */
        $bankSetup = BankSetup::find($id);

        if (empty($bankSetup)) {
            Flash::error('Bank Setup not found');

            return redirect(route('bankSetups.index'));
        }

        $bankSetup->delete();

        Flash::success('Bank Setup deleted successfully.');

        return redirect(route('bankSetups.index'));
    }
}
