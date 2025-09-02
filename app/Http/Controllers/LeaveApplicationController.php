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
        $leaveApplications = LeaveApplication::with(['user', 'leaveType'])->paginate(10);
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
        $users = User::pluck('name', 'id'); // For approved_by dropdown, if needed
        return view('leave_applications.create')->with(['leaveTypes' => $leaveTypes, 'users' => $users]);
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
        $input['user_id'] = Auth::id(); // Automatically set the current user as the applicant

        // Calculate leave days
        $startDate = Carbon::parse($input['start_date']);
        $endDate = Carbon::parse($input['end_date']);
        $requestedDays = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end day

        if (isset($input['is_half_day']) && $input['is_half_day']) {
            $requestedDays = 0.5;
        }

        $leaveType = LeaveType::find($input['leave_type_id']);

        // Check leave balance
        $user = User::find(Auth::id());
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

        LeaveApplication::create($input);

        Flash::success('Leave Application submitted successfully.');
        return redirect(route('leaveApplications.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leaveApplication = LeaveApplication::with(['user', 'leaveType', 'approver'])->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        return view('leave_applications.show')->with('leaveApplication', $leaveApplication);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $leaveTypes = LeaveType::pluck('name', 'id');
        $users = User::pluck('name', 'id'); // For approved_by dropdown, if needed

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        return view('leave_applications.edit')->with(['leaveApplication' => $leaveApplication, 'leaveTypes' => $leaveTypes, 'users' => $users]);
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
        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        $input = $request->all();

        // Recalculate requested days if dates changed
        $startDate = Carbon::parse($input['start_date']);
        $endDate = Carbon::parse($input['end_date']);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        if (isset($input['is_half_day']) && $input['is_half_day']) {
            $requestedDays = 0.5;
        }
        $input['requested_days'] = $requestedDays;

        $leaveType = LeaveType::find($input['leave_type_id']);

        // Check leave balance (only if status is Approved or becoming Approved)
        if (isset($input['status']) && $input['status'] === 'Approved') {
            $user = User::find($leaveApplication->user_id); // Get the applicant's gender
            $userGender = $user->gender;

            if ($leaveType->gender_criteria !== 'All' && $leaveType->gender_criteria !== $userGender) {
                Flash::error('This leave type is not available for the applicant\'s gender.');
                return redirect(route('leaveApplications.edit', $id));
            }

            $availableLeave = $leaveType->total_days_per_year;

            // Deduct already approved leaves for the current year, excluding the current application if it was already approved
            $usedLeaves = LeaveApplication::where('user_id', $leaveApplication->user_id)
                ->where('leave_type_id', $leaveType->id)
                ->where('status', 'Approved')
                ->whereYear('start_date', Carbon::now()->year)
                ->where('id', '!=', $leaveApplication->id) // Exclude current application's previously approved days
                ->sum('requested_days');

            if (($usedLeaves + $requestedDays) > $availableLeave) {
                Flash::error('Insufficient leave balance for this leave type for the applicant.');
                return redirect(route('leaveApplications.edit', $id));
            }
        }

        // Handle approval
        if (isset($input['status']) && $input['status'] === 'Approved' && $leaveApplication->status !== 'Approved') {
            $input['approved_by'] = Auth::id();
            $input['approved_at'] = Carbon::now();
        } elseif (isset($input['status']) && $input['status'] !== 'Approved' && $leaveApplication->status === 'Approved') {
            // If status changes from Approved to something else, clear approval info
            $input['approved_by'] = null;
            $input['approved_at'] = null;
        }

        $leaveApplication->update($input);

        Flash::success('Leave Application updated successfully.');
        return redirect(route('leaveApplications.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    public function approve($id)
    {
        if (!Auth::user()->can('approve_leave')) {
            Flash::error('You do not have permission to approve leaves.');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication->status = 'Approved';
        $leaveApplication->approved_by = Auth::id();
        $leaveApplication->approved_at = Carbon::now();
        $leaveApplication->save();

        Flash::success('Leave Application approved successfully.');
        return redirect(route('leaveApplications.index'));
    }

    public function reject($id)
    {
        if (!Auth::user()->can('approve_leave')) {
            Flash::error('You do not have permission to reject leaves.');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication = LeaveApplication::find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found');
            return redirect(route('leaveApplications.index'));
        }

        $leaveApplication->status = 'Rejected';
        $leaveApplication->approved_by = Auth::id(); // Still record who rejected
        $leaveApplication->approved_at = Carbon::now();
        $leaveApplication->save();

        Flash::success('Leave Application rejected successfully.');
        return redirect(route('leaveApplications.index'));
    }
}
