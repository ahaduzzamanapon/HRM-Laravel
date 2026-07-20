<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDeparture;
use App\Models\User;
use Illuminate\Http\Request;
use Flash;
use Response;

class EmployeeDepartureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeDepartures = EmployeeDeparture::with('user')->get();
        return view('employee_departures.index')->with('employeeDepartures', $employeeDepartures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id');
        return view('employee_departures.create')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $existing = EmployeeDeparture::where('user_id', $request->input('user_id'))->exists();
        if ($existing) {
            return response()->json(['error' => true, 'message' => 'Departure record already exists for this employee. Only one record is allowed.'], 400);
        }

        $input = $request->except('_token');
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/departure';
            $customName = 'departure-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        $departure = EmployeeDeparture::create($input);
        if($departure){
            $user = User::find($departure->user_id);
            if ($user) {
                $user->status = $departure->status;
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'Departure Track saved successfully.'], 200);
        } else {
            return response()->json(['error' => false, 'message' => 'Failed to save Departure Track.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employeeDeparture = EmployeeDeparture::find($id);

        if (empty($employeeDeparture)) {
            Flash::error('Departure Track not found');
            return redirect(route('employeeDepartures.index'));
        }

        return view('employee_departures.show')->with('employeeDeparture', $employeeDeparture);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employeeDeparture = EmployeeDeparture::find($id);
        if (empty($employeeDeparture)) {
            return response()->json(['error' => true, 'message' => 'Departure Track not found'], 404);
        }
        return response()->json(['employeeDeparture' => $employeeDeparture]);
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
        $employeeDeparture = EmployeeDeparture::find($id);

        if (empty($employeeDeparture)) {
            return response()->json(['error' => true, 'message' => 'Departure Track not found'], 404);
        }

        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/departure';
            $customName = 'departure-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']);
        }

        $employeeDeparture->update($input);

        $user = User::find($employeeDeparture->user_id);
        if ($user) {
            $user->status = $employeeDeparture->status;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Departure Track updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeDeparture = EmployeeDeparture::find($id);

        if (empty($employeeDeparture)) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => true, 'message' => 'Departure Track not found'], 404);
            }
            Flash::error('Departure Track not found');
            return redirect(route('employeeDepartures.index'));
        }

        if ($employeeDeparture->document && file_exists(public_path($employeeDeparture->document))) {
            unlink(public_path($employeeDeparture->document));
        }

        $userId = $employeeDeparture->user_id;
        $employeeDeparture->delete();

        $user = User::find($userId);
        if ($user) {
            $hasOtherDepartures = EmployeeDeparture::where('user_id', $userId)->exists();
            if (!$hasOtherDepartures) {
                $user->status = 'regular';
                $user->save();
            }
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Departure Track deleted successfully.'], 200);
        }

        Flash::success('Departure Track deleted successfully.');
        return redirect(route('employeeDepartures.index'));
    }

    public function list($user_id)
    {
        $departures = EmployeeDeparture::where('user_id', $user_id)->get();
        return response()->json(['success' => true, 'employeeDeparture' => $departures]);
    }
}
