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
        $authUser = \Illuminate\Support\Facades\Auth::user();
        if (\App\Services\AuthorizationEngine::isEmployeeRole($authUser)) {
            return $this->myAttendance($request);
        }

        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        $departments = Department::pluck('name', 'id');
        $designations = Designation::pluck('desi_name', 'id');

        $statusFilter = $request->input('status', 'regular');

        $usersQuery = User::select('users.*')
            ->where(function ($q) {
                $q->where('users.group_id', 3);
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'role_id')) {
                    $q->orWhere('users.role_id', 3);
                }
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                if ($statusFilter === 'terminate') {
                    return $query->whereIn(\DB::raw('LOWER(users.status)'), ['terminate', 'terminated']);
                }
                return $query->whereRaw('LOWER(users.status) = ?', [strtolower($statusFilter)]);
            })
            ->when($statusFilter === 'regular', function ($query) {
                return $query->whereDoesntHave('departures');
            })
            ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
            ->with(['branch', 'department', 'designation'])
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('users.branch_id', $request->branch_id);
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                return $query->where('users.department_id', $request->department_id);
            })
            ->when($request->filled('designation_id'), function ($query) use ($request) {
                return $query->where('users.designation_id', $request->designation_id);
            })
            ->orderByRaw('CAST(NULLIF(users.emp_id, "") AS UNSIGNED) ASC, users.emp_id ASC');

        applyBranchScope($usersQuery, 'users.branch_id');
        $users = $usersQuery->get();

        return view('attendance.process', compact('users', 'branches', 'departments', 'designations'));
    }

    public function process(Request $request)
    {
        $fromDate = $request->input('from_date');
        $userIds = $request->input('users');

        if (empty($userIds)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one user.']);
        }

        $result = $this->attendanceService->attn_process($fromDate, $userIds);

        if (empty($result['errors'])) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        } else {
            return response()->json(['success' => false, 'message' => $result['message'] . ": " . implode("; ", $result['errors'])]);
        }
    }

    public function getReportData(Request $request)
    {
        // dd($_POST);
        $reportType = $request->input('report_type');
        $filterType = $request->input('filter_type');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $userIds = $request->input('user_ids');

        $user = \Illuminate\Support\Facades\Auth::user();
        if (empty($userIds) || \App\Services\AuthorizationEngine::isEmployeeRole($user) || (optional($user->role)->name == 'Employee')) {
            $userIds = [$user->id];
        }
        $data = $this->attendanceService->getReportData($reportType, $filterType, $fromDate, $toDate, $userIds);
        $base = [
            'date' => $fromDate,
            'reportType' => $reportType,
            'filterType' => $filterType,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];

        if ($reportType === 'daily') {
            return view('attendance.daily_atten_report', array_merge($base, ['attendanceDatas' => $data]));
        }

        if ($reportType === 'other' && $filterType === 'job_card') {
            $data = $this->attendanceService->job_card($fromDate, $toDate, $userIds);
            return view('attendance.job_card', array_merge($base, ['job_card' => $data]));
        }
        if ($reportType === 'other' && $filterType === 'general_report') {
            $data = $this->attendanceService->general_report($userIds);
            return view('attendance.general_report', array_merge($base, ['general_reports' => $data]));
        }
        if ($reportType === 'other' && $filterType === 'emp_id_card') {
            $data = $this->attendanceService->general_report($userIds);
            return view('attendance.emp_id_card');
        }
        if ($reportType === 'other' && in_array($filterType, ['intime_only', 'outtime_only'])) {
            $attendanceDatas = \App\Models\AttendanceTime::with('user')
                ->whereBetween('attendance_date', [$fromDate, $toDate ?: $fromDate])
                ->whereIn('employee_id', $userIds)
                ->where('status', 'Present')
                ->orderBy('attendance_date')
                ->get();
            return view('attendance.daily_atten_report', array_merge($base, [
                'attendanceDatas' => $attendanceDatas,
            ]));
        }
        if ($reportType === 'other' && $filterType === 'branch_wise') {
            $records = \App\Models\AttendanceTime::with(['user.branch', 'user.department'])
                ->whereBetween('attendance_date', [$fromDate, $toDate ?: $fromDate])
                ->whereIn('employee_id', $userIds)
                ->orderBy('attendance_date')
                ->get();
            $branchData = $records->groupBy(function ($item) {
                return optional(optional($item->user)->branch)->branch_name ?? 'Unassigned';
            });
            return view('attendance.branch_wise_report', array_merge($base, ['branchData' => $branchData]));
        }

        return response()->json(['success' => false, 'message' => 'Invalid report parameters.']);
    }

    public function storeManualAttendance(Request $request)
    {
        $userIds = $request->input('users');
        $date = $request->input('date');
        $clockIn = $request->input('clock_in');
        $clockOut = $request->input('clock_out');

        if (empty($userIds)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one user.']);
        }

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if ($user->punch_id) {
                if ($clockIn) {
                    \App\Models\AttMachineData::create([
                        'punch_id' => $user->punch_id,
                        'date_time' => $date . ' ' . $clockIn . ':00',
                    ]);
                }
                if ($clockOut) {
                    \App\Models\AttMachineData::create([
                        'punch_id' => $user->punch_id,
                        'date_time' => $date . ' ' . $clockOut . ':00',
                    ]);
                }
            }
        }

        $this->attendanceService->attn_process($date, $userIds);

        return response()->json(['success' => true, 'message' => 'Manual attendance saved and processed successfully.']);
    }

    public function filterUsers(Request $request)
    {
        $statusFilter = $request->input('status', 'regular');

        $usersQuery = User::select('users.id', 'users.name', 'users.last_name', 'users.emp_id', 'users.branch_id', 'users.department_id', 'users.designation_id')
            ->where(function ($q) {
                $q->where('users.group_id', 3);
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'role_id')) {
                    $q->orWhere('users.role_id', 3);
                }
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                if ($statusFilter === 'terminate') {
                    return $query->whereIn(\DB::raw('LOWER(users.status)'), ['terminate', 'terminated']);
                }
                return $query->whereRaw('LOWER(users.status) = ?', [strtolower($statusFilter)]);
            })
            ->when($statusFilter === 'regular', function ($query) {
                return $query->whereDoesntHave('departures');
            })
            ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
            ->with(['branch', 'department', 'designation'])
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('users.branch_id', $request->branch_id);
            })
            ->when($request->filled('department_id'), function ($query) use ($request) {
                return $query->where('users.department_id', $request->department_id);
            })
            ->when($request->filled('designation_id'), function ($query) use ($request) {
                return $query->where('users.designation_id', $request->designation_id);
            })
            ->orderByRaw('CAST(NULLIF(users.emp_id, "") AS UNSIGNED) ASC, users.emp_id ASC');

        applyBranchScope($usersQuery, 'users.branch_id');
        $users = $usersQuery->get();

        return response()->json($users);
    }

    public function myAttendance(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $selectedDate = $request->input('date', date('Y-m-d'));
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        if ($request->filled('date')) {
            $carbonDate = \Carbon\Carbon::parse($selectedDate);
            if (!$request->filled('month')) {
                $selectedMonth = $carbonDate->format('m');
            }
            if (!$request->filled('year')) {
                $selectedYear = $carbonDate->format('Y');
            }
        }

        $startDate = \Carbon\Carbon::createFromDate($selectedYear, (int)$selectedMonth, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        $today = \Carbon\Carbon::today();
        $calcEndDate = ($startDate->isSameMonth($today)) ? $today : $endDate;

        // Fetch attendance records from database for this user in selected month
        $attendanceRecords = \App\Models\AttendanceTime::where('employee_id', $user->id)
            ->whereBetween('attendance_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->attendance_date)->format('Y-m-d');
            });

        // Fetch leave applications for this user in selected month
        $leaves = \App\Models\LeaveApplication::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            })->get();

        // Fetch holidays for all branches
        $holidays = \App\Models\Holyday::where('status', 'Published')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get();

        // Fetch shift details for employee
        $shiftId = $user->shift_id ?: 1;
        $shiftDetails = \App\Models\ShiftDetail::where('shift_id', $shiftId)->get()->keyBy('day_of_week');

        $dailyList = [];
        $activeCount = 0;
        $lateCount = 0;
        $absentCount = 0;
        $leaveCount = 0;

        $sl = 1;
        for ($date = $calcEndDate->copy(); $date->gte($startDate); $date->subDay()) {
            $dateStr = $date->format('Y-m-d');
            $dayName = $date->format('l');
            $record = $attendanceRecords->get($dateStr);
            $shift = $shiftDetails->get($dayName);

            $punchIn = '-';
            $punchOut = '-';
            $lateMinutes = 0;
            $officeHour = '0:0';
            $status = 'Absent';

            if ($record) {
                $status = $record->attendance_status ?: $record->status ?: 'Present';
                if ($record->clock_in) {
                    $punchIn = \Carbon\Carbon::parse($record->clock_in)->format('h:i A');
                }
                if ($record->clock_out) {
                    $punchOut = \Carbon\Carbon::parse($record->clock_out)->format('h:i A');
                } elseif ($date->isToday() && $record->clock_in) {
                    $punchOut = 'Continue';
                }

                $lateMinutes = (int) $record->late_time;

                if ($record->clock_in && $record->clock_out) {
                    $cIn = \Carbon\Carbon::parse($record->clock_in);
                    $cOut = \Carbon\Carbon::parse($record->clock_out);
                    $diffMins = $cIn->diffInMinutes($cOut);
                    $hrs = floor($diffMins / 60);
                    $mins = $diffMins % 60;
                    $officeHour = "{$hrs}:" . sprintf('%02d', $mins);
                }

                if (in_array(strtolower($status), ['present', 'late'])) {
                    $activeCount++;
                    if ($record->late_status == 1 || $lateMinutes > 0) {
                        $lateCount++;
                    }
                } elseif (strtolower($status) == 'absent') {
                    $absentCount++;
                }
            } else {
                $isLeave = $leaves->contains(function ($l) use ($dateStr) {
                    return $dateStr >= \Carbon\Carbon::parse($l->start_date)->format('Y-m-d') &&
                           $dateStr <= \Carbon\Carbon::parse($l->end_date)->format('Y-m-d');
                });

                if ($isLeave) {
                    $status = 'Leave';
                    $punchIn = 'Taking Leave';
                    $punchOut = 'Taking Leave';
                    $leaveCount++;
                } else {
                    $isHoliday = $holidays->contains(function ($h) use ($dateStr) {
                        $hStart = \Carbon\Carbon::parse($h->date)->format('Y-m-d');
                        $hEnd = $h->end_date ? \Carbon\Carbon::parse($h->end_date)->format('Y-m-d') : $hStart;
                        return $dateStr >= $hStart && $dateStr <= $hEnd;
                    });

                    if ($isHoliday) {
                        $status = 'Holiday';
                        $punchIn = 'Off Day';
                        $punchOut = 'Off Day';
                    } elseif ($shift && $shift->is_weekend) {
                        $status = 'Off Day';
                        $punchIn = 'Off Day';
                        $punchOut = 'Off Day';
                    } else {
                        $status = 'Absent';
                        $punchIn = 'Absent';
                        $punchOut = 'Absent';
                        $absentCount++;
                    }
                }
            }

            $dailyList[] = [
                'sl' => $sl++,
                'date' => $dateStr,
                'punch_in' => $punchIn,
                'punch_out' => $punchOut,
                'late' => $lateMinutes,
                'office_hour' => $officeHour,
                'status' => $status,
            ];
        }

        if ($request->ajax()) {
            return response()->json([
                'active_days' => $activeCount,
                'late_days' => $lateCount,
                'absent_days' => $absentCount,
                'leave_days' => $leaveCount,
                'daily_list' => $dailyList,
            ]);
        }

        return view('attendance.my_attendance', compact(
            'selectedDate',
            'selectedMonth',
            'selectedYear',
            'activeCount',
            'lateCount',
            'absentCount',
            'leaveCount',
            'dailyList'
        ));
    }

    public function getDailyReportData(Request $request)
    {
        $date = $request->input('date');
        $type = $request->input('type');
        $data = $this->attendanceService->getDailyReportData($date);
        if ($type == 1) {
            return view('attendance.daily_report', [
                'attendanceDatas' => $data['all'],
                'date' => $date
            ]);

        } else {
            return response()->json($data);
        }
    }
}
