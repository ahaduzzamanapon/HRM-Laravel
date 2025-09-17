<?php

namespace App\Services;

use App\Models\AttendanceTime;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    protected $db; // Assuming this is injected or available for CodeIgniter-like DB operations

    public function __construct()
    {
     
    }

    public function attn_process($process_date, $emp_ids, $status = null)
    {
        //dd($emp_ids);
        $employees = $this->get_employees($emp_ids, $status = null);

        //dd($employees);
        foreach ($employees as $row) {
           
            try {
                $joining_date = $row->date_of_joining;
                $emp_id      = $row->user_id;
                $shift_id = $row->shift_id;
                $in_time  = '';
                $out_time = '';
                $clock_in_out = 0;
                $late_status = 0;
                $late_time = 0;
                $production = 0;
                $ot = 0;
                $lunch_late_status = 0;
                $early_out_status = 0;

                // Assuming these are methods or properties available
                $shift_schedule  = $this->get_shift_schedule($emp_id, $process_date, $shift_id);
                dd($shift_schedule);
                $proxi_id   = $this->get_proxi($emp_id);

                // Ensure shift_schedule is not null before accessing properties
                if (!$shift_schedule) {
                    $errors[] = "Shift schedule not found for employee {$emp_id} on {$process_date}";
                    continue; // Skip to next employee
                }

                $in_start_time   = $shift_schedule->in_start_time;
                $late_start_time = $shift_schedule->late_start;
                $out_end_time    = $shift_schedule->out_end_time;
                $out_start_time  = $shift_schedule->out_start_time;
                $lunch_time      = $shift_schedule->lunch_time;
                $lunch_minute    = $shift_schedule->lunch_minute;
                $ot_start_time   = $shift_schedule->ot_start_time;

                // Convert to Carbon instances for easier manipulation
                $start_time_carbon = Carbon::parse($process_date.' '.$in_start_time);
                $actual_in_time_carbon  = Carbon::parse($process_date.' '.$shift_schedule->in_time);
                $end_time_carbon        = Carbon::parse($process_date.' '.$out_end_time);
                $out_start_time_carbon  = Carbon::parse($process_date.' '.$out_start_time);
                $late_start_time_carbon = Carbon::parse($process_date.' '.$late_start_time);
                $lunch_time_carbon      = Carbon::parse($process_date.' '.$lunch_time);
                $lunch_end_carbon       = $lunch_time_carbon->copy()->addMinutes($lunch_minute);
                $lunch_late_time_carbon = $lunch_end_carbon->copy()->addMinutes(5);
                $early_out_time_carbon  = Carbon::parse($process_date.' '.$ot_start_time);

                // get lunch in and out time and check lunch late status
                $half_evening_carbon = $early_out_time_carbon->copy()->subHours(3);
                $lunch_out   = $this->check_in_out_time($proxi_id, $lunch_time_carbon->toDateTimeString(), $lunch_end_carbon->toDateTimeString(), 'ASC');

                if ($lunch_out) {
                    $oott= Carbon::parse($lunch_out)->addMinutes(1)->toDateTimeString();
                }else {
                    $oott= $lunch_time_carbon->copy()->addMinutes(1)->toDateTimeString();
                }
                $lunch_in    = $this->check_in_out_time($proxi_id, $oott, $half_evening_carbon->toDateTimeString(), 'DESC');

                if ($lunch_in && Carbon::parse($lunch_in)->greaterThan($lunch_late_time_carbon)) {
                    $lunch_late_status = 1;
                }

                // get in time
                $in_time    = $this->check_in_out_time($proxi_id, $start_time_carbon->toDateTimeString(), $half_evening_carbon->toDateTimeString(), 'ASC');
                $movement_time = $this->check_movement_time($emp_id, $process_date, 'ASC');

                if ($movement_time && $movement_time->count() > 0) {
                    $move_out_time = $movement_time->first()->in_time;
                    if (!empty($move_out_time)  && Carbon::parse($move_out_time)->greaterThan(Carbon::parse($in_time))) {
                        $in_time = $move_out_time;
                    }
                    $move_in_time = $movement_time->first()->in_time;
                    if ($move_in_time != '' && Carbon::parse($move_in_time)->greaterThan($lunch_time_carbon)) {
                        $lunch_late_status = 0;
                    }
                }

                // get out time
                $out_time   = $this->check_in_out_time($proxi_id, $out_start_time_carbon->toDateTimeString(), $end_time_carbon->toDateTimeString(), 'DESC');
                $movement_time = $this->check_movement_time($emp_id, $process_date, 'DESC');

                if ($movement_time && $movement_time->count() > 0) {
                    $move_in_time = $movement_time->first()->out_time;
                    if ($move_in_time != '' && Carbon::parse($move_in_time)->greaterThan($early_out_time_carbon)) {
                        $out_time = $move_in_time;
                    }
                    $move_in_time = $movement_time->first()->in_time;
                    if ($move_in_time != '' && Carbon::parse($move_in_time)->greaterThan($lunch_time_carbon)) {
                        $lunch_late_status = 0;
                    }
                }

                // check leave
                $leave = $this->leave_chech($process_date, $emp_id);

                // check present status
                $status = '';
                $astatus = '';

                // Assuming $holiday_day and $off_day are determined elsewhere or passed in
                $holiday_day = false; // Placeholder
                $off_day = false; // Placeholder

                if ($leave['leave'] == true && $leave['Hleave'] == true) {
                    $astatus = 'Hleave';
                    $status = 'Hleave';
                    $half_morning_carbon = Carbon::parse($process_date.' '.'11:59:59');
                    if ($in_time && Carbon::parse($in_time)->lessThan($half_morning_carbon)) {
                        $astatus = 'HalfDay';
                    }
                    if ($out_time && Carbon::parse($out_time)->greaterThan($lunch_time_carbon)) {
                        $astatus = 'HalfDay';
                    }
                } elseif ($leave['leave'] == true) {
                    $astatus = 'Leave';
                    $status = 'Leave';
                } else {
                    if ($holiday_day == true) {
                        if (($in_time && Carbon::parse($in_time)->lessThan($out_start_time_carbon)) && ($out_time && Carbon::parse($out_time)->greaterThan($early_out_time_carbon))) {
                            $astatus = 'Present';
                            $status = 'Holiday';
                        } else {
                            $astatus = 'Holiday';
                            $status = 'Holiday';
                        }
                    } elseif ($off_day == true) {
                        if (($in_time && Carbon::parse($in_time)->lessThan($out_start_time_carbon)) && ($out_time && Carbon::parse($out_time)->greaterThanOrEqualTo($early_out_time_carbon))) {
                            $astatus = 'Present';
                            $status = 'Off Day';
                        } else {
                            $astatus = 'Off Day';
                            $status = 'Off Day';
                        }
                    }elseif ($in_time == '' && $out_time == '') {
                        $astatus = 'Absent';
                        $status = 'Absent';
                    }elseif($in_time != '' && $out_time == '') {
                        $astatus = 'HalfDay';
                        $status = 'HalfDay';
                    }elseif($in_time == '' && $out_time != '') {
                        $astatus = 'Absent';
                        $status = 'Absent';
                    }else{
                        $astatus = 'Absent';
                        $status = 'Present';
                        if ($in_time != '' && $out_time != '') {
                            $half_morning_carbon = Carbon::parse($process_date.' '.'11:59:59');
                            if (Carbon::parse($in_time)->greaterThan($half_morning_carbon)) {
                                $astatus = 'HalfDay';
                                $status = 'HalfDay';
                                $late_status= 0;
                            }
                            if (Carbon::parse($out_time)->lessThan($half_evening_carbon)) {
                                $astatus = 'HalfDay';
                                $status = 'HalfDay';
                                $late_status= 0;
                            }
                        }
                    }
                }

                $extra_p_day_carbon = $early_out_time_carbon->copy()->subHours(1);
                if ($off_day == true || $holiday_day == true) {
                    if (($off_day == true) && ($in_time && Carbon::parse($in_time)->lessThan($out_start_time_carbon)) && ($out_time && Carbon::parse($out_time)->greaterThanOrEqualTo($extra_p_day_carbon))) {
                        $astatus = 'Present';
                        $status = 'Off Day';
                        $late_status= 0;
                        if (in_array($emp_id, array(46, 82))) {
                            $astatus = 'Off Day';
                        }
                    }
                }

                if (($off_day == true) && ($in_time && Carbon::parse($in_time)->lessThan($out_start_time_carbon)) && ($out_time && Carbon::parse($out_time)->greaterThanOrEqualTo($early_out_time_carbon))) {
                    $astatus = 'Present';
                    $status = 'Off Day';
                }

                if ($in_time && Carbon::parse($in_time)->greaterThan($late_start_time_carbon) && Carbon::parse($in_time)->lessThan($lunch_time_carbon)) {
                    $late_status = 1;
                    $late_time = Carbon::parse($in_time)->diffInMinutes($actual_in_time_carbon);
                }

                if (($astatus == 'Present' || $status == 'Present') && $out_time != null && $in_time != null) {
                    $production = Carbon::parse($out_time)->diffInMinutes(Carbon::parse($in_time)) - $lunch_minute;
                    $actual_p_time = $early_out_time_carbon->diffInMinutes($actual_in_time_carbon) - $lunch_minute;
                    if($production > $actual_p_time) {
                        $ot = $production - $actual_p_time;
                    }
                } elseif (($astatus == 'HalfDay' || $status == 'HalfDay') && $out_time != null && $in_time != null) {
                    if ($in_time && Carbon::parse($in_time)->lessThan($lunch_time_carbon)) {
                        $production = Carbon::parse($out_time)->diffInMinutes(Carbon::parse($in_time)) -  $lunch_minute;
                    } else {
                        $production = Carbon::parse($out_time)->diffInMinutes(Carbon::parse($in_time));
                    }
                }

                if ($out_time && Carbon::parse($out_time)->lessThan($early_out_time_carbon)) {
                    $early_out_status = 1;
                }

                $data = array(
                    'employee_id'       => $emp_id,
                    'office_shift_id'   => 1,
                    'attendance_date'   => $process_date,
                    'clock_in'          => $in_time,
                    'clock_out'         => $out_time,
                    'production'        => $production,
                    'ot'                => $ot,
                    'late_time'         => $late_time,
                    'lunch_in'          => $lunch_in ? $lunch_in : '',
                    'lunch_out'         => $lunch_out ? $lunch_out : '',
                    'attendance_status' => $astatus,
                    'status'            => $status,
                    'late_status'       => $late_status,
                    'lunch_late_status' => $lunch_late_status,
                    'early_out_status'  => $early_out_status,
                );

                $attendanceRecord = AttendanceTime::where('employee_id', $emp_id)
                                                ->where('attendance_date', $process_date)
                                                ->first();

                if($attendanceRecord) {
                    $attendanceRecord->update($data);
                } else {
                    AttendanceTime::create($data);
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing employee {$emp_id} for date {$process_date}: " . $e->getMessage();
            }
        }

        return [
            'message' => empty($errors) ? 'Successfully Process Done' : 'Process completed with errors',
        ];
    }

    // Placeholder for helper functions - you need to implement these based on your application's logic
    protected function get_employees($emp_ids, $status = null)
    {
        return User::whereIn('id', $emp_ids)->get();
      
    }

    protected function get_shift_schedule($emp_id, $process_date, $shift_id)
    {
        // Determine the day of the week (0 for Sunday, 6 for Saturday)
        $day_of_week = Carbon::parse($process_date)->dayOfWeek;

        // Find the shift detail for the given shift_id and day of the week
        $shiftDetail = \App\Models\ShiftDetail::where('shift_id', $shift_id)
                                            ->where('day_of_week', $day_of_week)
                                            ->first();

        if ($shiftDetail) {
            // Calculate lunch_minute
            $lunch_start = Carbon::parse($shiftDetail->lunch_start_time);
            $lunch_end = Carbon::parse($shiftDetail->lunch_end_time);
            $lunch_minute = $lunch_start->diffInMinutes($lunch_end);

            return (object)[
                'in_start_time' => $shiftDetail->in_time,
                'late_start' => $shiftDetail->late_start_time,
                'out_end_time' => $shiftDetail->out_time,
                'out_start_time' => $shiftDetail->out_time, // Assuming out_start_time is same as out_time
                'lunch_time' => $shiftDetail->lunch_start_time,
                'lunch_minute' => $lunch_minute,
                'ot_start_time' => $shiftDetail->out_time, // Assuming OT starts after regular out_time
                'in_time' => $shiftDetail->in_time, // Used as actual_in_time in attn_process
            ];
        }

        // Return null or a default shift schedule if not found
        return null;
    }

    protected function get_proxi($emp_id)
    {
        // Example: return Proxi::where('employee_id', $emp_id)->value('proxi_id');
        return 'dummy_proxi_id';
    }

    protected function check_in_out_time($proxi_id, $start_time, $end_time, $order)
    {
        // Example: return AttMachineData::where('proxi_id', $proxi_id)->whereBetween('timestamp', [$start_time, $end_time])->orderBy('timestamp', $order)->value('timestamp');
        return null; // Return null for now
    }

    protected function check_movement_time($emp_id, $process_date, $order)
    {
        // Example: return EmployeeMoveRegister::where('employee_id', $emp_id)->where('date', $process_date)->orderBy('timestamp', $order)->get();
        return collect(); // Return an empty collection for now
    }

    protected function leave_chech($process_date, $emp_id)
    {
        // Example: return LeaveApplication::where('employee_id', $emp_id)->where('date', $process_date)->first();
        return ['leave' => false, 'Hleave' => false]; // Return dummy data for now
    }

    protected function checking_absent_after_offday_holiday($emp_id, $check_day)
    {
        // Implement logic here
    }

    protected function checking_absent_after_before_holiday($emp_id, $check_day)
    {
        // Implement logic here
    }

    protected function checking_absent_after_before_offday_holiday($emp_id, $check_day)
    {
        // Implement logic here
    }

    protected function leave_cal_all($emp_id, $process_date)
    {
        // Implement logic here
    }
}
