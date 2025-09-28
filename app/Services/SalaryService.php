<?php

namespace App\Services;

use App\Models\AttendanceTime;
use App\Models\Payroll;
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
                $rows = $this->count_attendance_status_wise($emp_id, $first_date, $end_date);
                $present = ($rows->present + $rows->HalfDay);
                $leaves = ($rows->leaves);
                $weekend = ($rows->weekend);
                $holiday = ($rows->holiday);
                $absent = $num_of_days - $present - $leaves - $weekend - $holiday;
                $pay_day = $num_of_days - $absent;
                //=======PRESENT STATUS END======

                //======= salary calculation here ==========//
                $perday_salary = round(($salary / $num_of_days), 2);
                // pay salary
                $pay_salary = round(($perday_salary * $pay_day), 2);

                // ------- Allowance Calculation here  ------- //
                $allows = $this->get_allowances($emp_id);
                // ------- Allowance Calculation end  ------- //
                $h_rent = ($allows['House Rent'] > 0) ? $allows['House Rent'] : 0;
                $m_allow = ($allows['Medical Allowance'] > 0) ? $allows['Medical Allowance'] : 0;
                $trans_allow = ($allows['Transport Allowance'] > 0) ? $allows['Transport Allowance'] : 0;
                $f_allow = ($allows['Food Allowance'] > 0) ? $allows['Food Allowance'] * $present : 0;


                // before after absent deduction
                $aba_deduct = round(($ba_absent * $perday_salary), 2);
                // absent deduction
                $absent_deduct = round(($perday_salary * $absent), 2);
                $total_deduct = $aba_deduct + $absent_deduct;

                // ======= salary calculation end ========== //


                $net_salary = round(($salary - ($total_deduct)), 2);

                $data = array(
                    'user_id'           => $emp_id,
                    'branch_id'         => $row->branch_id,
                    'emp_type'          => $row->emp_type,
                    'dept_id'           => $row->department_id,
                    'desig_id'          => $row->designation_id,
                    'emp_status'        => $row->status,
                    'salary_month'      => $first_date,
                    'n_days'            => $num_of_days,
                    'present'           => $present > 0 ? $present : 0,
                    'absent'            => $absent > 0 ? $absent : 0,
                    'leaves'            => $leaves > 0 ? $leaves : 0,
                    'weekend'           => $weekend > 0 ? $weekend : 0,
                    'holiday'           => $holiday > 0 ? $holiday : 0,
                    'pay_day'           => $pay_day > 0 ? $pay_day : 0,
                    'grade'             => $row->salary_grade_id,
                    'b_salary'          => $salary  > 0 ? $salary : 0,
                    'g_salary'          => $gross_salary > 0 ? $gross_salary : 0,
                    'pay_salary'        => $pay_salary > 0 ? $pay_salary : 0,

                    'h_rent'            => 0,
                    'm_allow'           => 0,
                    'f_allow'           => 0,
                    'special_allow'     => 0,
                    'child_allow'       => 0,
                    'trans_allow'       => 0,
                    'pf_allow_bank'     => 0,
                    'total_allow'       => 0,
                    'all_allows'        => 0,

                    'gross_salary'      => 0,
                    'absent_deduct'     => 0,
                    'pf_deduct'         => 0,
                    'tax_deduct'        => 0,
                    'bene_deduct'       => 0,
                    'auto_mobile_d'     => 0,
                    'h_loan_deduct'     => 0,
                    'p_loan_deduct'     => 0,

                    'loan_deduct'       => 0,
                    'stump_deduct'      => 0,
                    'others_deduct'     => 0,
                    'total_deduct'      => $total_deduct > 0 ? $total_deduct : 0,
                    'net_salary'        => $net_salary > 0 ? $net_salary : 0,

                    'created_at'        => date('d-m-Y h:i:s'),
                    'updated_at'        => date('d-m-Y h:i:s'),
                    'updated_by'        => auth()->user()->id
                );

                Payroll::updateOrCreate(
                    [
                        'user_id' => $emp_id,
                        'salary_month' => $first_date
                    ],
                    $data
                );
            } catch (\Exception $e) {
                $errors[] = "Error processing employee {$emp_id} : " . $e->getMessage();
            }
        }

        return [
            'message' => empty($errors) ? 'Successfully Process Done' : 'Process completed with errors',
            'errors' => $errors
        ];
    }

    // allowances cal
    function get_allowances($emp_id)
    {
        $userWithAllowances = User::with('userAllowances.allowanceSetting')->find($emp_id);
        $array = array();
        foreach ($userWithAllowances->userAllowances as $userAllowance) {
            // Access user allowance properties
            $isEnabled = $userAllowance->is_enabled;

            // Access allowance setting properties
            $allowanceName = $userAllowance->allowanceSetting->name;
            $allowanceType = $userAllowance->allowanceSetting->type;

            if ($userAllowance->custom_value != null) {
                $userAllowance->allowanceSetting->value = $userAllowance->custom_value;
            }

            if ($allowanceType == 'percentage') {
                $allowanceValue = $userWithAllowances->basic_salary *($userAllowance->allowanceSetting->value / 100);
            } else {
                $allowanceValue = $userAllowance->allowanceSetting->value;
            }
            $array[$allowanceName] = $isEnabled ? number_format((float)$allowanceValue, 2, '.', '') : '0.00';
        }
        return $array;
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
            ->select([
                \DB::raw('SUM(CASE WHEN status = \'Present\' THEN 1 ELSE 0 END ) AS present'),
                \DB::raw('SUM(CASE WHEN status = \'Absent\' THEN 1 ELSE 0 END ) AS absent'),
                \DB::raw('SUM(CASE WHEN status = \'Off Day\' THEN 1 ELSE 0 END ) AS weekend'),
                \DB::raw('SUM(CASE WHEN status = \'Holiday\' THEN 1 ELSE 0 END ) AS holiday'),
                \DB::raw('SUM(CASE WHEN attendance_status = \'HalfDay\' THEN 0.5 ELSE 0 END ) AS HalfDay'),
                \DB::raw('SUM(CASE WHEN status = \'Leave\' THEN 1 ELSE 0 END ) AS leaves'),
                \DB::raw('SUM(CASE WHEN extra_ap = 1 THEN 1 ELSE 0 END) AS extra_p'),
                \DB::raw('SUM(CASE WHEN late_status = \'1\' THEN 1 ELSE 0 END ) AS late_status'),
            ])
            ->first();

        return $query;

    }


}
