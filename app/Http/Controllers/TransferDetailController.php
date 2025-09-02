<?php

namespace App\Http\Controllers;

use App\Models\TransferDetail;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class TransferDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transferDetails = TransferDetail::paginate(10);
        return view('transfer_details.index')->with('transferDetails', $transferDetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('transfer_details.create')->with('users', $users);
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

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/transfer';
            $customName = 'transfer-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        TransferDetail::create($input);

        Flash::success('Transfer Detail saved successfully.');
        return redirect(route('transferDetails.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transferDetail = TransferDetail::find($id);

        if (empty($transferDetail)) {
            Flash::error('Transfer Detail not found');
            return redirect(route('transferDetails.index'));
        }

        return view('transfer_details.show')->with('transferDetail', $transferDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transferDetail = TransferDetail::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown

        if (empty($transferDetail)) {
            Flash::error('Transfer Detail not found');
            return redirect(route('transferDetails.index'));
        }

        return view('transfer_details.edit')->with(['transferDetail' => $transferDetail, 'users' => $users]);
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
        $transferDetail = TransferDetail::find($id);

        if (empty($transferDetail)) {
            Flash::error('Transfer Detail not found');
            return redirect(route('transferDetails.index'));
        }

        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/transfer';
            $customName = 'transfer-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']); // Don't update document if not provided
        }

        $transferDetail->update($input);

        Flash::success('Transfer Detail updated successfully.');
        return redirect(route('transferDetails.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transferDetail = TransferDetail::find($id);

        if (empty($transferDetail)) {
            Flash::error('Transfer Detail not found');
            return redirect(route('transferDetails.index'));
        }

        // Delete associated document if exists
        if ($transferDetail->document && file_exists(public_path($transferDetail->document))) {
            unlink(public_path($transferDetail->document));
        }

        $transferDetail->delete();

        Flash::success('Transfer Detail deleted successfully.');
        return redirect(route('transferDetails.index'));
    }
}
