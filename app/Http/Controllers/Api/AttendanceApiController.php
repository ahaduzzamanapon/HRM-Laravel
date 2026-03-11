<?php

namespace App\Http\Controllers\Api;

use App\Models\AttendanceTime;
use App\Models\User;
use App\Models\Holyday;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceApiController extends BaseApiController
{
    /**
     * GET /api/v1/attendance/my
     * Returns authenticated user's attendance records
     */
    public function myAttendance(Request $request)
    {
        $user = $request->user();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $records = AttendanceTime::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
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
            'year' => 'required|integer|min:2000',
        ]);

        $users = User::with('shift')
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->user_id, fn($q) => $q->where('id', $request->user_id))
            ->where('status', 'active')
            ->get();

        $attendance = AttendanceTime::whereYear('date', $request->year)
            ->whereMonth('date', $request->month)
            ->get()
            ->groupBy('user_id');

        $result = $users->map(function ($user) use ($attendance) {
            $records = $attendance->get($user->id, collect());
            return [
                'user_id' => $user->id,
                'name' => $user->name . ' ' . $user->last_name,
                'emp_id' => $user->emp_id,
                'total_present' => $records->where('status', 'present')->count(),
                'total_absent' => $records->where('status', 'absent')->count(),
                'total_late' => $records->where('late', 1)->count(),
                'records' => $records,
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
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'in_time' => 'nullable',
            'out_time' => 'nullable',
            'status' => 'required|in:present,absent,holiday,leave',
            'note' => 'nullable|string',
        ]);

        $record = AttendanceTime::updateOrCreate(
            ['user_id' => $validated['user_id'], 'date' => $validated['date']],
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
            ->whereDate('date', $date)
            ->get();

        $summary = [
            'date' => $date,
            'total' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('late', 1)->count(),
            'on_leave' => $records->where('status', 'leave')->count(),
            'records' => $records,
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
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->where('status', 'active')
            ->get(['id', 'name', 'last_name', 'emp_id', 'department_id', 'branch_id', 'shift_id', 'designation_id']);

        return $this->successResponse($users);
    }

    /**
     * POST /api/v1/attendance/process
     * Bulk process attendance for a month
     */
    public function process(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        return $this->successResponse(null, 'Attendance processing queued. Please use the web panel for bulk processing.');
    }
}
