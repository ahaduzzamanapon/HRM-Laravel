<?php

namespace App\Services;

use App\Models\AttendanceTime;
use App\Models\LeaveApplication;
use App\Models\Holyday;
use App\Models\User;
use App\Models\ShiftDetail;
use App\Models\BiometricAttendanceLog;
use Carbon\Carbon;

class AttendanceService
{
    public function __construct()
    {

    }

    public function attn_process($process_date, $emp_ids)
    {
        $employees = $this->get_employees($emp_ids);
        $errors = [];

        foreach ($employees as $row) {

            try {
                $joining_date = $row->date_of_joining;
                $emp_id      = $row->id;
                $shift_id = $row->shift_id;
                $branch_id = $row->branch_id;

                $shift_schedule  = $this->get_shift_schedule($emp_id, $process_date, $shift_id);
                // dd($shift_schedule);
                if (!$shift_schedule) {
                    AttendanceTime::updateOrCreate(
                        [
                            'employee_id' => $emp_id,
                            'attendance_date' => $process_date
                        ],
                        [
                            'office_shift_id'   => $shift_id,
                            'attendance_status' => 'Absent',
                            'status'            => 'Absent',
                        ]
                    );
                    $errors[] = "Shift schedule not found for employee {$emp_id} on {$process_date}. Marked as Absent.";
                    continue; // Skip to next employee
                }

                $in_time  = null;
                $out_time = null;

                // --- Collect timestamps from BOTH sources ---
                $allTimestamps = collect();

                // Source 1: att_machine_data (file upload / manual)
                $punch_id = $row->punch_id ?: $row->biometric_id;
                if ($punch_id) {
                    $machineRecords = \App\Models\AttMachineData::where('punch_id', $punch_id)
                        ->whereDate('date_time', $process_date)
                        ->pluck('date_time');
                    $allTimestamps = $allTimestamps->merge($machineRecords);
                }

                // Source 2: biometric_attendance_logs (ZKTeco device)
                if ($row->biometric_id) {
                    $bioRecords = BiometricAttendanceLog::where('biometric_user_id', $row->biometric_id)
                        ->whereDate('timestamp', $process_date)
                        ->pluck('timestamp');
                    $allTimestamps = $allTimestamps->merge($bioRecords);
                }

                // Sort and pick first/last
                $allTimestamps = $allTimestamps->map(fn($t) => Carbon::parse($t))->sort()->values();

                if ($allTimestamps->count() > 0) {
                    $in_time  = $allTimestamps->first()->format('Y-m-d H:i:s');
                    $out_time = $allTimestamps->count() > 1
                        ? $allTimestamps->last()->format('Y-m-d H:i:s')
                        : null;
                }

                $late_status = 0;
                $late_time = 0;

                // Calculate late status and late time
                if ($in_time && Carbon::parse($in_time)->greaterThan(Carbon::parse($process_date . ' ' . $shift_schedule->late_start))) {
                    $late_status = 1;
                    $late_time = Carbon::parse($in_time)->diffInMinutes(Carbon::parse($process_date . ' ' . $shift_schedule->in_time));
                }

                // check leave
                $leave = $this->leave_check($process_date, $emp_id);
                $holiday_day = $this->holiday_check($process_date, $branch_id);

                $attendance_status = 'Absent';
                $status = 'Absent';
                if ($holiday_day == true) {
                    $attendance_status = 'Holiday';
                    $status = 'Holiday';
                } elseif ($shift_schedule->is_weekend == 1) {
                    $attendance_status = 'Off Day';
                    $status = 'Off Day';
                    $late_status = 0;
                    $late_time = 0;
                    $in_time  = null;
                    $out_time = null;
                } elseif ($in_time && $out_time && $in_time != $out_time) {
                    $attendance_status = 'Present';
                    $status = 'Present';
                } elseif ($in_time && ($out_time == null || $in_time == $out_time)) {
                    $attendance_status = 'Continue';
                    $status = 'Present'; // Set status to Present as requested
                    $out_time = null; // Ensure clock_out is null if status is Continue
                } elseif ($in_time || $out_time) {
                    $attendance_status = 'HalfDay';
                    $status = 'HalfDay';
                } elseif ($leave['leave'] == true) {
                    $attendance_status = 'Leave';
                    $status = 'Leave';
                }

                $data = array(
                    'employee_id'       => $emp_id,
                    'office_shift_id'   => $shift_id,
                    'attendance_date'   => $process_date,
                    'clock_in'          => $in_time,
                    'clock_out'         => $out_time,
                    'production'        => 0,
                    'ot'                => 0,
                    'late_time'         => $late_time,
                    'lunch_in'          => null,
                    'lunch_out'         => null,
                    'attendance_status' => $attendance_status,
                    'status'            => $status,
                    'late_status'       => $late_status,
                    'lunch_late_status' => 0,
                    'early_out_status'  => 0,
                );
                // dd($data);
                AttendanceTime::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'attendance_date' => $process_date
                    ],
                    $data
                );
            } catch (\Exception $e) {
                $errors[] = "Error processing employee {$emp_id} for date {$process_date}: " . $e->getMessage();
            }
        }

        return [
            'message' => empty($errors) ? 'Successfully Process Done' : 'Process completed with errors',
            'errors' => $errors
        ];
    }

    protected function get_employees($emp_ids)
    {


        if (is_array($emp_ids)) {
            return User::whereIn('id', $emp_ids)->where('id', '!=', 1)->get();
        } else {
            return User::where('id', $emp_ids)->where('id', '!=', 1)->get();
        }
    }

    protected function get_shift_schedule($emp_id, $process_date, $shift_id)
    {
        $day_of_week = Carbon::parse($process_date)->format('l');

        $shiftDetail = \App\Models\ShiftDetail::where('shift_id', $shift_id)->where('day_of_week', $day_of_week)->first();

        if ($shiftDetail) {
            $lunch_start = Carbon::parse($shiftDetail->lunch_start_time);
            $lunch_end = Carbon::parse($shiftDetail->lunch_end_time);
            $lunch_minute = $lunch_start->diffInMinutes($lunch_end);

            return (object)[
                'in_start_time' => $shiftDetail->in_time,
                'late_start' => $shiftDetail->late_start_time,
                'out_end_time' => $shiftDetail->out_time,
                'out_start_time' => $shiftDetail->out_time,
                'lunch_time' => $shiftDetail->lunch_start_time,
                'lunch_minute' => $lunch_minute,
                'ot_start_time' => $shiftDetail->out_time,
                'in_time' => $shiftDetail->in_time,
                'is_weekend' => $shiftDetail->is_weekend
            ];
        }
        return null;
    }

    public function leave_check($process_date, $emp_id)
    {
        $query = LeaveApplication::where('start_date', '<=', $process_date)
            ->where('end_date', '>=', $process_date)
            ->where('user_id', $emp_id)
            ->where('status', 'approved')
            ->get();

        if(!empty($query[0])){
            $leave = array(
                'HLeave' => false,
                'leave'  => true
            );
        } else {
            $leave = array(
                'HLeave' => false,
                'leave'  => false
            );
        }
        return $leave;
    }
    public function holiday_check($process_date, $branch_id)
    {
        $query = Holyday::where('date', '=', $process_date)
            ->where('branch_id', $branch_id)
            ->where('status', 'Published')
            ->first();

        if(empty($query)){
            return false;
        } else {
            return true;
        }
    }

    public function getReportData($reportType, $filterType, $fromDate, $toDate, $userIds)
    {
        $query = AttendanceTime::with('user');

        if ($reportType == 'daily') {
            if($filterType == 'all'){
                $query->where('attendance_date', $fromDate);
            }elseif($filterType == 'present'){
                $query->where('attendance_date', $fromDate);
                $query->where('attendance_status', 'Present');
                $query->where('status', 'Present');
            }elseif($filterType == 'absent'){
                $query->where('attendance_date', $fromDate);
                $query->where('attendance_status', 'Absent');
                $query->where('status', 'Absent');
            }elseif($filterType == 'late'){
                $query->where('attendance_date', $fromDate);
                $query->where('late_status', 1);
            }
        }
        if ($filterType != 'all') {
            if ($filterType == 'leave') {
                $query->where('status', 'Leave')->orWhere('status', 'HLeave');
            }
        }
        if (!empty($userIds)) {
            $query->whereIn('employee_id', $userIds);
        }
        // dd($query->get());
        return $query->get();
    }

    public function getDailyReportData($date){
        $all     = AttendanceTime::with('user')->whereDate('attendance_date', $date)->get();
        $present = $all->where('status', 'Present');
        $absent  =  $all->where('status', 'Absent');
        $late    =  $all->where('late_status', 1);
        $result = [
            'all'          => $all,
            'present'      => $present,
            'absent'       => $absent,
            'late'         => $late,
            'present_count'=> (clone $all)->where('status', 'Present')->count(),
            'absent_count' => (clone $all)->where('status', 'Absent')->count(),
            'late_count'   => (clone $all)->where('late_status', 1)->count(),
            'all_employees'=> $all->count()
        ];
        return $result;
    }

    public function job_card($fromDate, $toDate, $userIds){ // array of employee IDs

        $attendances = AttendanceTime::select(
            'employee_id',
            'attendance_date',
            'clock_in',
            'clock_out',
            'late_status',
            'attendance_status',
            'status'
        )
        ->whereBetween('attendance_date', [$fromDate, $toDate])
        ->whereIn('employee_id', $userIds)
        ->with('user:id,name,last_name,emp_id')
        ->orderBy('employee_id')
        ->orderBy('attendance_date')
        ->get();

        $grouped = $attendances->groupBy('employee_id');
        return $grouped;
    }
    public function general_report($userIds){
        $general_report = User::with('department', 'designation')
                        ->whereIn('id', $userIds)
                        ->get();

        // dd($general_report->user());
        return $general_report;
    }


}
