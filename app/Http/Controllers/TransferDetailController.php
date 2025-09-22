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
        $input = $request->except(['_token']); // Exclude _token

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/transfer';
            $customName = 'transfer-document-' . time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        // Get the user's current branch ID for old_branch
        $user = User::find($input['user_id']);
        $input['old_branch'] = $user->branch_id ?? null; // Set old_branch to user's current branch ID

        // Save transfer details
        $transferDetail = TransferDetail::create($input);

        // Update user's branch only if status is Approved
        if (isset($input['status']) && $input['status'] === 'Approved') {
            if ($user) {
                $user->branch_id = $input['new_branch'];
                $user->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Transfer Detail saved successfully.']);
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
            return response()->json(['error' => true, 'message' => 'Transfer Detail not found'], 404);
        }

        return response()->json(['transferDetails' => $transferDetail, 'users' => $users], 200);
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
            return response()->json(['error' => true, 'message' => 'Transfer Detail not found'], 404);
        }
        $input = $request->except(['_token']); // Exclude _token
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/transfer';
            $customName = 'transfer-document-' . time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']); // Don't update document if not provided
        }
        // Update transfer details
        $transferDetail->update($input);
        // Update user's branch only if status is Approved and new_branch is provided and different
        if (isset($input['status']) && $input['status'] === 'Approved') {
            $user = User::find($input['user_id']);
            if ($user && $user->branch_id != $input['new_branch']) {
                $user->branch_id = $input['new_branch'];
                $user->save();
            }
        }
        return response()->json(['success' => true, 'message' => 'Transfer Detail updated successfully.']);
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
            return response()->json(['success' => false, 'message' => 'Transfer Detail not found'], 404);
        }

        // Delete associated document if exists
        if ($transferDetail->document && file_exists(public_path($transferDetail->document))) {
            unlink(public_path($transferDetail->document));
        }
        $transferDetail->delete();
        return response()->json(['success' => true, 'message' => 'Transfer Detail deleted successfully.']);
    }
    public function list($user_id)
    {
        $transferDetails = TransferDetail::where('user_id', $user_id)->get();
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return response()->json(['transferDetails' => $transferDetails, 'users' => $users], 200);
    }
}
