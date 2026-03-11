<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\LeaveApplication;
use App\Models\AttendanceTime;
use App\Models\Payroll;
use App\Models\Notice;
use Illuminate\Http\Request;

class DashboardApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $month = now()->month;
        $year = now()->year;

        $totalEmployees = User::where('status', 'active')->count();
        $presentToday = AttendanceTime::whereDate('date', $today)->where('status', 'present')->count();
        $absentToday = AttendanceTime::whereDate('date', $today)->where('status', 'absent')->count();
        $pendingLeaves = LeaveApplication::where('status', 'pending')->count();
        $totalPayroll = Payroll::where('month', $month)->where('year', $year)->sum('net_salary');
        $latestNotices = Notice::orderByDesc('created_at')->take(5)->get(['id', 'title', 'created_at']);
        $newEmployees = User::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

        // Attendance trend for last 7 days
        $attendanceTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $attendanceTrend[] = [
                'date' => $date,
                'present' => AttendanceTime::whereDate('date', $date)->where('status', 'present')->count(),
                'absent' => AttendanceTime::whereDate('date', $date)->where('status', 'absent')->count(),
            ];
        }

        return $this->successResponse([
            'total_employees' => $totalEmployees,
            'present_today' => $presentToday,
            'absent_today' => $absentToday,
            'pending_leaves' => $pendingLeaves,
            'monthly_payroll' => $totalPayroll,
            'new_employees_month' => $newEmployees,
            'latest_notices' => $latestNotices,
            'attendance_trend' => $attendanceTrend,
        ]);
    }

    public function myDashboard(Request $request)
    {
        $user = $request->user();
        $month = now()->month;
        $year = now()->year;

        $myAttendance = AttendanceTime::where('user_id', $user->id)->whereMonth('date', $month)->whereYear('date', $year);
        $payslip = Payroll::where('user_id', $user->id)->where('month', $month)->where('year', $year)->first();
        $leaveBalance = LeaveApplication::where('user_id', $user->id)->where('status', 'approved')->whereYear('from_date', $year)->count();

        return $this->successResponse([
            'employee' => [
                'id' => $user->id,
                'name' => $user->name . ' ' . $user->last_name,
                'emp_id' => $user->emp_id,
                'designation' => optional($user->designation)->name,
                'department' => optional($user->department)->name,
            ],
            'attendance_this_month' => [
                'present' => $myAttendance->clone()->where('status', 'present')->count(),
                'absent' => $myAttendance->clone()->where('status', 'absent')->count(),
                'late' => $myAttendance->clone()->where('late', 1)->count(),
            ],
            'payslip_this_month' => $payslip,
            'approved_leaves_this_year' => $leaveBalance,
            'pf_balance' => $user->provident_fund_balance ?? 0,
        ]);
    }
}
