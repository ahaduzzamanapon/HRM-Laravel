<?php

namespace App\Services;

use App\Models\AttendanceTime;
use App\Models\User;
use Carbon\Carbon;

class SalaryService
{
    public function __construct()
    {

    }

    public function salary_process($salary_month, $emp_ids)
    {
        $first_date = Carbon::parse($salary_month)->format('Y-m-01');
        $end_date = Carbon::parse($salary_month)->format('Y-m-t');
        $process_month = Carbon::parse($salary_month)->format('Y-m');
        $num_of_days = Carbon::parse($salary_month)->daysInMonth;
        $employees = $this->get_employees($emp_ids);
        $errors = [];

        foreach ($employees as $row) {
            try {
                $doj         = $row->date_of_join;
                $emp_id      = $row->id;
                $salary      = $row->basic_salary;
                $gross_salary = $row->gross_salary;

                $join_month = trim(substr($doj,0,7));
                if (strtotime($join_month) > strtotime($salary_month)) {
                    continue;
                }

                //=======PRESENT STATUS======
                if($salary_month == $join_month)
                {
                    $ba_absent = $this->get_days($first_date, $doj) - 1;
                    $first_date = $doj;
                }
                else
                {
                    $ba_absent = 0;
                }

                //=======PRESENT STATUS ======
                $rows = $this->count_attendance_status_wise($emp_id,$first_date,$end_date);

                $present = ($rows->attend + $rows->HalfDay) - ($rows->present_error2 + $rows->present_error1);


                $leaves = $leave->el + $leave->sl;
                $extra_attend = $rows->extra_p;
                // $extra_attend = ($rows->extra_p + $rows->meeting) - $rows->attend;
                $absent = $num_of_days - ($leaves + $rows->weekend + $rows->holiday + $present + $ba_absent);


                // dd($rows);
                //=======PRESENT STATUS END======

                //======= salary calculation here ==========//
                $perday_salary = round(($salary / $num_of_days), 2);

                // before after absent deduction
                $aba_deduct = 0;
                $aba_deduct = round(($ba_absent * $perday_salary), 2);
                // absent deduction
                $absent_deduct = 0;
                $absent_deduct = round(($perday_salary * $absent), 2);

                // late deduction
                $late_deduct = 0;
                $late_day = 0;
                if ($rows->late_status > 2) {
                    $late_day = floor($rows->late_status / 3);
                    $late_deduct = round(($perday_salary * $late_day), 2);
                }

                // extra pay salary
                $extra_pay = 0;
                $extra_pay = round(($perday_salary * $extra_attend), 2);


                // pay salary
                $pay_salary = round(($salary - ($late_deduct + $absent_deduct)), 2);

                $advanced_salary = $this->db->select('SUM(approved_amount) as approved_amount')->where('emp_id',$emp_id )->where('effective_month',$process_month)->get('xin_advance_salaries')->row();
                $advanced = 0;
                if(!empty($advanced_salary) && $advanced_salary->approved_amount > 0){
                    $advanced = $advanced_salary->approved_amount;
                }

                $data = array(
                    'employee_id' => $emp_id,
                    'emp_status' => $emp_status,
                    'department_id' => $department_id,
                    'designation_id' => $designation_id,
                    'company_id' => 1,
                    'location_id' => 1,
                    'salary_month' => $salary_month,
                    'basic_salary' => $salary,
                    'present' => $present,
                    'extra_p' => ($extra_attend != null) ? $extra_attend:0,
                    'ba_absent' => $ba_absent,
                    'absent' => $absent,
                    'holiday' => ($rows->holiday != null) ? $rows->holiday:0,
                    'weekend' => ($rows->weekend != null) ? $rows->weekend:0,
                    'earn_leave' => ($leave->el != null) ? $leave->el:0,
                    'sick_leave' => ($leave->sl != null) ? $leave->sl:0,
                    'late_count' => ($rows->late_status != null) ? $rows->late_status:0,
                    'd_day'   => $late_day,
                    'late_deduct' => $late_deduct,
                    'aba_deduct' => $aba_deduct,
                    'absent_deduct' => $absent_deduct,
                    'm_pay_day'    => 0,
                    'modify_salary' => 0,
                    'other_payment' => $extra_pay,
                    'advanced_salary' => $advanced,
                    'wages_type' => 1,
                    'is_half_monthly_payroll' => 0,
                    'total_commissions' => 0,
                    'total_statutory_deductions' => 0,
                    'total_allowances' => 0,
                    'total_loan' => 0,
                    'total_overtime' => 0,
                    'is_payment' => '1',
                    'status' => '0',
                    'payslip_type' => 'full_monthly',
                    'payslip_key' =>  random_string('alnum', 40),
                    'year_to_date' => date('d-m-Y'),
                    'created_at' => date('d-m-Y h:i:s')
                );

                $data['lunch_deduct'] = $lunch_deduct;
                $data['net_salary'] = $pay_salary - $lunch_deduct;
                $data['grand_net_salary' ] = (($pay_salary + $extra_pay ) - $advanced) - $lunch_deduct;

                $query = $this->db->where('salary_month',$salary_month)->where('employee_id',$emp_id)->get('xin_salary_payslips');
                if ($query->num_rows() > 0) {
                    $data['modify_salary'] = $query->row()->modify_salary;
                    $data['m_pay_day'] = $query->row()->m_pay_day;

                    $this->db->where('payslip_id', $query->row()->payslip_id);
                    $this->db->update('xin_salary_payslips',$data);
                } else {
                    $this->db->insert('xin_salary_payslips', $data);
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

    function get_days($from, $to)
    {
        $first_date = strtotime($from);
        $second_date = strtotime($to);
        $offset = $second_date - $first_date;
        $total_days = floor($offset/60/60/24);
        return $total_days + 1;
    }

    function count_attendance_status_wise($emp_id,$FS_on_date,$FS_off_date)
    {
        $query = AttendanceTime::where('employee_id', $emp_id)
            ->whereBetween('attendance_date', [$FS_on_date, $FS_off_date])
            ->selectRaw("
                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END ) AS attend,
                SUM(CASE WHEN status = 'Absent'   THEN 1 ELSE 0 END ) AS absent,
                SUM(CASE WHEN status = 'Off Day'  THEN 1 ELSE 0 END ) AS weekend,
                SUM(CASE WHEN status = 'Holiday'  THEN 1 ELSE 0 END ) AS holiday,
                SUM(CASE WHEN attendance_status = 'HalfDay'  THEN 0.5 ELSE 0 END ) AS HalfDay,
                SUM(CASE WHEN status = 'Present' AND clock_in = '' AND clock_out != '' THEN 0.5 ELSE 0 END ) AS present_error1,
                SUM(CASE WHEN status = 'Present' AND clock_in != '' AND clock_out = '' THEN 0.5 ELSE 0 END ) AS present_error2,
                SUM(CASE WHEN extra_ap = 1 THEN 1 ELSE 0 END) AS extra_p,
                SUM(CASE WHEN late_status = '1' THEN 1 ELSE 0 END ) AS late_status,
            ")
            ->first();
            
        return $query;

    }


}
