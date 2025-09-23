<?php

namespace App\Services;

use App\Models\AttendanceTime;
use App\Models\User;
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

                $shift_schedule  = $this->get_shift_schedule($emp_id, $process_date, $shift_id);

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

                $punch_id = $row->punch_id;

                $in_time_record = \App\Models\AttMachineData::where('punch_id', $punch_id)
                                                            ->whereDate('date_time', $process_date)
                                                            ->orderBy('date_time', 'asc')
                                                            ->first();

                $out_time_record = \App\Models\AttMachineData::where('punch_id', $punch_id)
                                                             ->whereDate('date_time', $process_date)
                                                             ->orderBy('date_time', 'desc')
                                                             ->first();

                if ($in_time_record) {
                    $in_time = $in_time_record->date_time;
                }

                if ($out_time_record) {
                    $out_time = $out_time_record->date_time;
                }

                $late_status = 0;
                $late_time = 0;

                // Calculate late status and late time
                if ($in_time && Carbon::parse($in_time)->greaterThan(Carbon::parse($process_date . ' ' . $shift_schedule->late_start))) {
                    $late_status = 1;
                    $late_time = Carbon::parse($in_time)->diffInMinutes(Carbon::parse($process_date . ' ' . $shift_schedule->in_time));
                }

                                $attendance_status = 'Absent';
                $status = 'Absent';

                if ($in_time && $out_time && $in_time != $out_time) {
                    $attendance_status = 'Present';
                    $status = 'Present';
                } elseif ($in_time && ($out_time == null || $in_time == $out_time)) {
                    $attendance_status = 'Continue';
                    $status = 'Present'; // Set status to Present as requested
                    $out_time = null; // Ensure clock_out is null if status is Continue
                } elseif ($in_time || $out_time) {
                    $attendance_status = 'HalfDay';
                    $status = 'HalfDay';
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
        return User::whereIn('id', $emp_ids)->get();
    }

    protected function get_shift_schedule($emp_id, $process_date, $shift_id)
    {
        $day_of_week = Carbon::parse($process_date)->format('l');

        $shiftDetail = \App\Models\ShiftDetail::where('shift_id', $shift_id)
                                            ->where('day_of_week', $day_of_week)
                                            ->first();

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
            ];
        }
        return null;
    }

    public function getReportData($reportType, $filterType, $fromDate, $toDate, $userIds)
    {
        $query = AttendanceTime::with('user');

        if ($reportType == 'daily') {
            $query->whereDate('attendance_date', $fromDate);
        } elseif ($reportType == 'monthly') {
            $query->whereMonth('attendance_date', Carbon::parse($fromDate)->month);
            $query->whereYear('attendance_date', Carbon::parse($fromDate)->year);
        } elseif ($reportType == 'continue') {
            $query->whereBetween('attendance_date', [$fromDate, $toDate]);
        }

        if ($filterType != 'all') {
            if ($filterType == 'leave') {
                $query->where('status', 'Leave')->orWhere('status', 'HLeave');
            } else {
                $query->where('status', $filterType);
            }
        }

        if (!empty($userIds)) {
            $query->whereIn('employee_id', $userIds);
        }

        return $query->get();
    }
}
