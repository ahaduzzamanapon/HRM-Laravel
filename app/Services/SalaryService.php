<?php

namespace App\Services;

use App\Models\AttendanceTime;
use App\Models\Payroll;
use App\Models\ChildAllowance;
use App\Models\ProvidentFundSetting;
use App\Models\TaxSetup;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                $h_rent = isset($allows['House Rent']) ? $allows['House Rent'] : 0;
                $m_allow = isset($allows['Medical Allowance']) ? $allows['Medical Allowance'] : 0;
                $trans_allow = isset($allows['Transport Allowance']) ? $allows['Transport Allowance'] : 0;
                $f_allow = isset($allows['Food Allowance']) ? $allows['Food Allowance'] * $present : 0;

                // child allowance
                $child_allow = $this->get_child_allowances($emp_id, $first_date);
                // pf allowance
                $pf_emp = 0; $pf_bank = 0; $interest_rate = 0;
                if ($row->is_pf_member == 1) {
                    $pf_a_bank = $this->get_pf_allowance($salary);
                    $pf_emp = isset($pf_a_bank['employee_contribution']) ? (float)$pf_a_bank['employee_contribution'] : 0.0;
                    $pf_bank = isset($pf_a_bank['employer_contribution']) ? (float)$pf_a_bank['employer_contribution'] : 0.0;
                    $interest_rate = isset($pf_a_bank['interest_rate']) ? (float)$pf_a_bank['interest_rate'] : 0.0;
                }
                // total allowance
                $total_allow = $h_rent + $m_allow + $f_allow + $child_allow + $trans_allow;
                $total_gross = ($pay_salary + $total_allow);
                // ------- Allowance Calculation end  ------- //

                // ------- Deduction Calculation here ------- //
                // before after absent deduction
                $aba_deduct = round(($ba_absent * $perday_salary), 2);
                // absent deduction
                $absent_deduct = round(($perday_salary * $absent), 2);
                // total absent deduction
                $total_ab_deduct = $aba_deduct + $absent_deduct;

                //  tax deduction
                $tax_deduct = 0;
                $tax_deduct = $this->get_tax_deduction($total_gross);
                // auto mobile deduction
                $loans = $this->get_loans_deduction($emp_id);
                $h_loan_deduct = isset($loans['Housing Loan']) ? (float)$loans['Housing Loan'] : 0.00;
                $p_loan_deduct = isset($loans['Personal Loan']) ? (float)$loans['Personal Loan'] : 0.00;
                $auto_mobile_d = isset($loans['Motorcycle/Scooter Loan']) ? (float)$loans['Motorcycle/Scooter Loan'] : 0.00;
                $total_deduct = $total_ab_deduct + $tax_deduct + $h_loan_deduct + $p_loan_deduct + $auto_mobile_d + $pf_emp;


                // ------- Deduction Calculation end ------- //
                // ======= salary calculation end ========== //

                $net_salary = round(($total_gross - $total_deduct - 100 - 10), 2);
                $data = array(
                    'user_id'           => $emp_id,
                    'branch_id'         => $row->branch_id,
                    'emp_type'          => $row->emp_type,
                    'dept_id'           => $row->department_id,
                    'desig_id'          => $row->designation_id,
                    'emp_status'        => $row->status,
                    'pay_type'          => $row->pay_type,
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

                    'h_rent'            => $h_rent,
                    'm_allow'           => $m_allow,
                    'f_allow'           => $f_allow,
                    'special_allow'     => 0,
                    'child_allow'       => $child_allow,
                    'trans_allow'       => $trans_allow,
                    'pf_allow_bank'     => $pf_bank,
                    'total_allow'       => $total_allow > 0 ? $total_allow : 0,
                    'all_allows'        => 0,

                    'gross_salary'      => $total_gross > 0 ? $total_gross : 0,
                    'absent_deduct'     => $total_ab_deduct > 0 ? $total_ab_deduct : 0,
                    'pf_deduct'         => $pf_emp,
                    'tax_deduct'        => $tax_deduct > 0 ? $tax_deduct : 0,
                    'bene_deduct'       => 100,
                    'h_loan_deduct'     => $h_loan_deduct > 0 ? $h_loan_deduct : 0,
                    'p_loan_deduct'     => $p_loan_deduct > 0 ? $p_loan_deduct : 0,
                    'auto_mobile_d'     => $auto_mobile_d > 0 ? $auto_mobile_d : 0,

                    'stump_deduct'      => 10,
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

    // loan deduction cal
    function get_loans_deduction($emp_id)
    {
        $loans = DB::table('loans')
            ->join('loan_types', 'loan_types.id', '=', 'loans.loan_type_id')
            ->select('loans.*', 'loan_types.name')
            ->where('loans.employee_id', $emp_id)
            ->where('loans.status', 'Disbursed')
            ->get();
            // to day work

        $array = array();
        if (!empty($loans[0])) {
            foreach ($loans as $key => $value) {
                $array[$value->name] = $value->monthly_installment;
            }
        }
        return $array;
    }

    // tax deduction cal
    function get_tax_deduction($salary)
    {
        $tax = TaxSetup::where('min_salary', '<=', $salary)->where('max_salary', '>=', $salary)->first();
        $tax_deduct = 0;
        if (!empty($tax)) {
            $tax_deduct = $tax->tax_monthly;
        }
        return $tax_deduct;
    }

    // pf allowances cal
    function get_pf_allowance($salary)
    {
        $pfs = ProvidentFundSetting::first();

        $employee_contribution = 0;
        $employer_contribution = 0;
        $interest_rate = 0;
        if (!empty($pfs)) {
            $employee_contribution = ($salary * ($pfs->employee_contribution / 100));
            $employer_contribution = ($salary * ($pfs->employer_contribution / 100));
            $interest_rate = $pfs->interest_rate;
        }

        $array = array(
            'employee_contribution' => $employee_contribution,
            'employer_contribution' => $employer_contribution,
            'interest_rate' => $interest_rate
        );
        return $array;
    }
    // child allowances cal
    function get_child_allowances($emp_id, $first_date)
    {
        $childAllowances = ChildAllowance::where('user_id', $emp_id)
        ->where('start_month', '<=', $first_date)->where('end_month', '>=', $first_date)
        ->get();

        $array = 0;
        if (!empty($childAllowances[0])) {
            foreach ($childAllowances as $childAllowance) {
                $array += $childAllowance->pay_amt;
            }
        }
        return $array;
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
