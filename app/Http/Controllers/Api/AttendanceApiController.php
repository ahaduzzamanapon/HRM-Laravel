<?php

namespace App\Http\Controllers\Api;

use App\Models\AttendanceTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceApiController extends BaseApiController
{
    /**
     * GET /api/v1/attendance/my
     */
    public function myAttendance(Request $request)
    {
        $user = $request->user();
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $records = AttendanceTime::where('employee_id', $user->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->orderBy('attendance_date')
            ->get();

        return $this->successResponse($records);
    }

    /**
     * GET /api/v1/attendance/report
     */
    public function report(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|min:2000',
        ]);

        $users = User::with('shift')
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->branch_id,     fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->user_id,       fn($q) => $q->where('id', $request->user_id))
            ->where('status', 'active')
            ->get();

        $attendance = AttendanceTime::whereYear('attendance_date', $request->year)
            ->whereMonth('attendance_date', $request->month)
            ->get()
            ->groupBy('employee_id');

        $result = $users->map(function ($user) use ($attendance) {
            $records = $attendance->get($user->id, collect());
            return [
                'user_id'       => $user->id,
                'name'          => $user->name . ' ' . $user->last_name,
                'emp_id'        => $user->emp_id,
                'total_present' => $records->where('attendance_status', 'present')->count(),
                'total_absent'  => $records->where('attendance_status', 'absent')->count(),
                'total_late'    => $records->where('late_status', 1)->count(),
                'records'       => $records,
            ];
        });

        return $this->successResponse($result);
    }

    /**
     * POST /api/v1/attendance/manual
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'employee_id'       => 'required|exists:users,id',
            'attendance_date'   => 'required|date',
            'clock_in'          => 'nullable|string',
            'clock_out'         => 'nullable|string',
            'attendance_status' => 'required|in:present,absent,holiday,leave',
            'remarks'           => 'nullable|string',
        ]);

        $record = AttendanceTime::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'attendance_date' => $validated['attendance_date']],
            $validated
        );

        return $this->successResponse($record, 'Attendance saved successfully');
    }

    /**
     * GET /api/v1/attendance/daily-report
     */
    public function dailyReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $records = AttendanceTime::with(['user:id,name,last_name,emp_id,department_id', 'user.department:id,name'])
            ->whereDate('attendance_date', $date)
            ->get();

        $summary = [
            'date'     => $date,
            'total'    => $records->count(),
            'present'  => $records->where('attendance_status', 'present')->count(),
            'absent'   => $records->where('attendance_status', 'absent')->count(),
            'late'     => $records->where('late_status', 1)->count(),
            'on_leave' => $records->where('attendance_status', 'leave')->count(),
            'records'  => $records,
        ];

        return $this->successResponse($summary);
    }

    /**
     * GET /api/v1/attendance/filter
     */
    public function filterUsers(Request $request)
    {
        $users = User::with(['department', 'branch', 'designation', 'shift'])
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->branch_id,     fn($q) => $q->where('branch_id', $request->branch_id))
            ->where('status', 'active')
            ->get(['id', 'name', 'last_name', 'emp_id', 'department_id', 'branch_id', 'shift_id', 'designation_id']);

        return $this->successResponse($users);
    }

    /**
     * POST /api/v1/attendance/process
     */
    public function process(Request $request)
    {
        $request->validate([
            'month'      => 'required|integer|between:1,12',
            'year'       => 'required|integer|min:2000',
            'user_ids'   => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        return $this->successResponse(null, 'Attendance processing queued. Please use the web panel for bulk processing.');
    }
}
