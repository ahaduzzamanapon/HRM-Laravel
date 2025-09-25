<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $query = LeaveApplication::with(['user', 'leaveType', 'approver', 'finalApprover']);

        if ($user->role->name == 'Employee') {
            $query->where('user_id', $user->id);
        }

        $leaveApplications = $query->paginate(10);
        return view('leave_applications.index')->with('leaveApplications', $leaveApplications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leaveTypes = LeaveType::pluck('name', 'id');
        return view('leave_applications.create')->with('leaveTypes', $leaveTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $input = $request->all();
        
        if (!Auth::check()) {
            Flash::error('You must be logged in to apply for leave.');
            return redirect(route('login'));
        }

        $input['user_id'] = Auth::id();

        $startDate = Carbon::parse($input['start_date']);
        $endDate = Carbon::parse($input['end_date']);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        if (isset($input['is_half_day']) && $input['is_half_day']) {
            $requestedDays = 0.5;
        }

        $leaveType = LeaveType::find($input['leave_type_id']);
        $user = Auth::user();
        $userGender = $user->gender;

        if ($leaveType->gender_criteria !== 'All' && $leaveType->gender_criteria !== $userGender) {
            Flash::error('This leave type is not available for your gender.');
            return redirect(route('leaveApplications.create'));
        }

        $availableLeave = $leaveType->total_days_per_year;
        $usedLeaves = LeaveApplication::where('user_id', Auth::id())
            ->where('leave_type_id', $leaveType->id)
            ->where('status', 'Approved')
            ->whereYear('start_date', Carbon::now()->year)
            ->sum('requested_days');

        if (($usedLeaves + $requestedDays) > $availableLeave) {
            Flash::error('Insufficient leave balance for this leave type.');
            return redirect(route('leaveApplications.create'));
        }

        $input['requested_days'] = $requestedDays;
        $input['status'] = 'Pending';
        $input['approver_level'] = 'first_level';

        // Find first level approver (HR)
        $firstApprover = User::whereHas('role', function($q){
            $q->where('name', 'HR');
        })->first();
        $input['approver_id'] = $firstApprover ? $firstApprover->id : null;

        LeaveApplication::create($input);

        Flash::success('Leave Application submitted successfully.');
        return redirect(route('leaveApplications.index'));
    }

    public function show($id)
    {
        $leaveApplication = LeaveApplication::with(['user', 'leaveType', 'approver', 'finalApprover'])->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        return view('leave_applications.show')->with('leaveApplication', $leaveApplication);
    }

    public function edit($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $leaveTypes = LeaveType::pluck('name', 'id');

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        return view('leave_applications.edit')->with(['leaveApplication' => $leaveApplication, 'leaveTypes' => $leaveTypes]);
    }

    public function update(Request $request, $id)
    {
        // This method might need to be adjusted based on your workflow for updates.
        // For now, it will have basic update functionality.
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        $input = $request->all();
        $leaveApplication->update($input);

        Flash::success('Leave Application updated successfully.');
        return redirect(route('leaveApplications.index'));
    }

    public function destroy($id)
    {
        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication->delete();

        Flash::success('Leave Application deleted successfully.');
        return redirect(route('leaveApplications.index'));
    }

    public function firstLevelApprove($id)
    {
        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found or you are not authorized to approve.');
            return redirect(route('leaveApplications.index'));
        }

        // Find final approver (Admin)
        $finalApprover = User::whereHas('role', function($q){
            $q->where('name', 'Admin');
        })->first();

        $leaveApplication->status = 'First Level Approved';
        $leaveApplication->approver_level = 'final_level';
        $leaveApplication->final_approver_id = $finalApprover ? $finalApprover->id : null;
        $leaveApplication->save();

        Flash::success('Leave Application approved at first level.');
        return redirect(route('leaveApplications.index'));
    }

    public function finalApprove($id)
    {
        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found or you are not authorized for final approval.');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication->status = 'Approved';
        $leaveApplication->approver_level = 'approved';
        $leaveApplication->approved_by = Auth::id();
        $leaveApplication->approved_at = Carbon::now();
        $leaveApplication->save();

        Flash::success('Leave Application approved successfully.');
        return redirect(route('leaveApplications.index'));
    }

    public function reject($id)
    {
        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication->status = 'Rejected';
        $leaveApplication->save();

        Flash::success('Leave Application rejected successfully.');
        return redirect(route('leaveApplications.index'));
    }
}