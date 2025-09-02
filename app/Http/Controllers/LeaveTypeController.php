<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaveTypes = LeaveType::paginate(10);
        return view('leave_types.index')->with('leaveTypes', $leaveTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leave_types.create');
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

        LeaveType::create($input);

        Flash::success('Leave Type saved successfully.');
        return redirect(route('leaveTypes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leaveType = LeaveType::find($id);

        if (empty($leaveType)) {
            Flash::error('Leave Type not found');
            return redirect(route('leaveTypes.index'));
        }

        return view('leave_types.show')->with('leaveType', $leaveType);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leaveType = LeaveType::find($id);

        if (empty($leaveType)) {
            Flash::error('Leave Type not found');
            return redirect(route('leaveTypes.index'));
        }

        return view('leave_types.edit')->with('leaveType', $leaveType);
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
        $leaveType = LeaveType::find($id);

        if (empty($leaveType)) {
            Flash::error('Leave Type not found');
            return redirect(route('leaveTypes.index'));
        }

        $leaveType->update($request->all());

        Flash::success('Leave Type updated successfully.');
        return redirect(route('leaveTypes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leaveType = LeaveType::find($id);

        if (empty($leaveType)) {
            Flash::error('Leave Type not found');
            return redirect(route('leaveTypes.index'));
        }

        $leaveType->delete();

        Flash::success('Leave Type deleted successfully.');
        return redirect(route('leaveTypes.index'));
    }
}
