<?php

namespace App\Http\Controllers;

use App\Models\NomineeInformation;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class NomineeInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nomineeInformation = NomineeInformation::paginate(10);
        return view('nominee_information.index')->with('nomineeInformation', $nomineeInformation);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('nominee_information.create')->with('users', $users);
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

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $folder = 'images/nominee';
            $customName = 'nominee-photo-'.time();
            $input['photo'] = uploadFile($file, $folder, $customName);
        }

        NomineeInformation::create($input);

        Flash::success('Nominee Information saved successfully.');
        return redirect(route('nomineeInformation.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nomineeInformation = NomineeInformation::find($id);

        if (empty($nomineeInformation)) {
            Flash::error('Nominee Information not found');
            return redirect(route('nomineeInformation.index'));
        }

        return view('nominee_information.show')->with('nomineeInformation', $nomineeInformation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nomineeInformation = NomineeInformation::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown

        if (empty($nomineeInformation)) {
            Flash::error('Nominee Information not found');
            return redirect(route('nomineeInformation.index'));
        }

        return view('nominee_information.edit')->with(['nomineeInformation' => $nomineeInformation, 'users' => $users]);
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
        $nomineeInformation = NomineeInformation::find($id);

        if (empty($nomineeInformation)) {
            Flash::error('Nominee Information not found');
            return redirect(route('nomineeInformation.index'));
        }

        $input = $request->all();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $folder = 'images/nominee';
            $customName = 'nominee-photo-'.time();
            $input['photo'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['photo']); // Don't update photo if not provided
        }

        $nomineeInformation->update($input);

        Flash::success('Nominee Information updated successfully.');
        return redirect(route('nomineeInformation.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nomineeInformation = NomineeInformation::find($id);

        if (empty($nomineeInformation)) {
            Flash::error('Nominee Information not found');
            return redirect(route('nomineeInformation.index'));
        }

        // Delete associated photo if exists
        if ($nomineeInformation->photo && file_exists(public_path($nomineeInformation->photo))) {
            unlink(public_path($nomineeInformation->photo));
        }

        $nomineeInformation->delete();

        Flash::success('Nominee Information deleted successfully.');
        return redirect(route('nomineeInformation.index'));
    }
}
