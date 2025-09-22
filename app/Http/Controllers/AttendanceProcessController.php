<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Flash;

class AttendanceProcessController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        $branches = Branch::pluck('branch_name', 'id');
        $departments = Department::pluck('name', 'id');
        $designations = Designation::pluck('desi_name', 'id');

        $users = User::with(['branch', 'department', 'designation'])
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                return $query->where('department_id', $request->department_id);
            })
            ->when($request->filled('designation_id'), function ($query) use ($request) {
                return $query->where('designation_id', $request->designation_id);
            })
            ->get();

        return view('attendance.process', compact('users', 'branches', 'departments', 'designations'));
    }

    public function process(Request $request)
    {
        $fromDate = $request->input('from_date');
        $userIds = $request->input('users');

        if (empty($userIds)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one user.']);
        }

        $result = $this->attendanceService->attn_process($fromDate,  $userIds);

        if (empty($result['errors'])) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        } else {
            return response()->json(['success' => false, 'message' => $result['message'] . ": " . implode("; ", $result['errors'])]);
        }
    }

    public function filterUsers(Request $request)
    {
        $users = User::with(['branch', 'department', 'designation'])
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                return $query->where('department_id', $request->department_id);
            })
            ->when($request->filled('designation_id'), function ($query) use ($request) {
                return $query->where('designation_id', $request->designation_id);
            })
            ->get();

        return response()->json($users);
    }

}