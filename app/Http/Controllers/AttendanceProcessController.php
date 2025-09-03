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
        $toDate = $request->input('to_date');
        $userIds = $request->input('users');

        $result = $this->attendanceService->attn_process($fromDate, $toDate, $userIds);

        if (empty($result['errors'])) {
            Flash::success($result['message']);
        } else {
            Flash::error($result['message'] . ": " . implode("; ", $result['errors']));
        }
        return redirect()->back();
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