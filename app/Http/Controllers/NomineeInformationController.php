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
        // dd($request->all());
        $input = $request->except('_token');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $folder = 'images/nominee';
            $customName = 'nominee-photo-'.time();
            $input['photo'] = uploadFile($file, $folder, $customName);
        }

        NomineeInformation::create($input);

        return response()->json(['success' => true, 'message' => 'Nominee Information saved successfully.'], 200);
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
            return response()->json(['error' => true, 'message' => 'Nominee Information not found'], 404);
        }

        return response()->json(['nomineeInformation' => $nomineeInformation], 200);
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
            return response()->json(['error' => true, 'message' => 'Nominee Information not found'], 404);
        }

        return response()->json(['nomineeInformation' => $nomineeInformation, 'users' => $users], 200);
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
             return response()->json(['error' => true, 'message' => 'Nominee Information not found'], 404);
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

        return response()->json(['success' => true, 'message' => 'Nominee Information updated successfully.'], 200);
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
            return response()->json(['error' => 'Nominee Information not found'], 404);
        }

        // Delete associated photo if exists
        if ($nomineeInformation->photo && file_exists(public_path($nomineeInformation->photo))) {
            unlink(public_path($nomineeInformation->photo));
        }

        $nomineeInformation->delete();

        return response()->json(['success' => true, 'message' => 'Nominee Information deleted successfully.'], 200);
    }

    public function list($user_id)
    {
        $users = NomineeInformation::where('user_id', $user_id)->get();

        return response()->json(['sucess' => true,'nomineeInformation' => $users]);
    }
}
