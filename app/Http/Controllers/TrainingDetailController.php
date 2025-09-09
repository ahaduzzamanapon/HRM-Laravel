<?php

namespace App\Http\Controllers;

use App\Models\TrainingDetail;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class TrainingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainingDetails = TrainingDetail::paginate(10);
        return view('training_details.index')->with('trainingDetails', $trainingDetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('training_details.create')->with('users', $users);
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
            $folder = 'documents/training';
            $customName = 'training-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        TrainingDetail::create($input);

        Flash::success('Training Detail saved successfully.');
        return redirect(route('trainingDetails.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainingDetail = TrainingDetail::find($id);

        if (empty($trainingDetail)) {
            Flash::error('Training Detail not found');
            return redirect(route('trainingDetails.index'));
        }

        return view('training_details.show')->with('trainingDetail', $trainingDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainingDetail = TrainingDetail::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown

        if (empty($trainingDetail)) {
            Flash::error('Training Detail not found');
            return redirect(route('trainingDetails.index'));
        }

        return view('training_details.edit')->with(['trainingDetail' => $trainingDetail, 'users' => $users]);
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
        $trainingDetail = TrainingDetail::find($id);

        if (empty($trainingDetail)) {
            Flash::error('Training Detail not found');
            return redirect(route('trainingDetails.index'));
        }

        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/training';
            $customName = 'training-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']); // Don't update document if not provided
        }

        $trainingDetail->update($input);

        Flash::success('Training Detail updated successfully.');
        return redirect(route('trainingDetails.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainingDetail = TrainingDetail::find($id);

        if (empty($trainingDetail)) {
            Flash::error('Training Detail not found');
            return redirect(route('trainingDetails.index'));
        }

        // Delete associated document if exists
        if ($trainingDetail->document && file_exists(public_path($trainingDetail->document))) {
            unlink(public_path($trainingDetail->document));
        }

        $trainingDetail->delete();

        Flash::success('Training Detail deleted successfully.');
        return redirect(route('trainingDetails.index'));
    }
}
