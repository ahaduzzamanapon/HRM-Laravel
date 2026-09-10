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
                // bonus allowance (dynamic connection to employee bonus)
                $bonus_allow = $this->get_bonus_allowance($emp_id, $first_date, $salary, $gross_salary, $row->religion);
                
                // pf allowance
                $pf_emp = 0; $pf_bank = 0; $interest_rate = 0;
                if ($row->is_pf_member == 1) {
                    $pf_a_bank = $this->get_pf_allowance($salary);
                    $pf_emp = isset($pf_a_bank['employee_contribution']) ? (float)$pf_a_bank['employee_contribution'] : 0.0;
                    $pf_bank = isset($pf_a_bank['employer_contribution']) ? (float)$pf_a_bank['employer_contribution'] : 0.0;
                    $interest_rate = isset($pf_a_bank['interest_rate']) ? (float)$pf_a_bank['interest_rate'] : 0.0;
                }
                // total allowance
                $total_allow = $h_rent + $m_allow + $f_allow + $child_allow + $trans_allow + $bonus_allow;
                $total_gross = ($pay_salary + $total_allow);
                // ------- Allowance Calculation end  ------- //

                // ------- Deduction Calculation here ------- //
                // before after absent deduction
                $aba_deduct = round(($ba_absent * $perday_salary), 2);
                // absent deduction
                $absent_deduct = round(($perday_salary * $absent), 2);
                // total absent deduction
                $total_ab_deduct = $aba_deduct + $absent_deduct;

                // dynamic tax deduction
                $tax_deduct = $this->get_tax_deduction($emp_id, $total_gross);
                // loan deduction calculation
                $loansData = $this->get_loans_deduction($emp_id, $first_date);
                $h_loan_deduct = isset($loansData['Housing Loan']) ? (float)$loansData['Housing Loan'] : 0.00;
                $p_loan_deduct = isset($loansData['Personal Loan']) ? (float)$loansData['Personal Loan'] : 0.00;
                $auto_mobile_d = isset($loansData['Motorcycle/Scooter Loan']) ? (float)$loansData['Motorcycle/Scooter Loan'] : 0.00;
                $other_loan_d = isset($loansData['Other Loan']) ? (float)$loansData['Other Loan'] : 0.00;
                $total_loan_deduct = isset($loansData['total_loan_deduction']) ? (float)$loansData['total_loan_deduction'] : ($h_loan_deduct + $p_loan_deduct + $auto_mobile_d + $other_loan_d);

                $total_deduct = $total_ab_deduct + $tax_deduct + $total_loan_deduct + $pf_emp;

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
                    'special_allow'     => $bonus_allow,
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
                    'others_deduct'     => $other_loan_d > 0 ? $other_loan_d : 0,
                    'total_deduct'      => $total_deduct > 0 ? $total_deduct : 0,
                    'net_salary'        => $net_salary > 0 ? $net_salary : 0,

                    'created_at'        => date('d-m-Y h:i:s'),
                    'updated_at'        => date('d-m-Y h:i:s'),
                    'updated_by'        => auth()->check() ? auth()->id() : 1
                );

                $payrollRecord = Payroll::updateOrCreate(
                    [
                        'user_id' => $emp_id,
                        'salary_month' => $first_date
                    ],
                    $data
                );

                // Record Loan Repayment Entries and update Loan Balances
                if (!empty($loansData['loan_details'])) {
                    foreach ($loansData['loan_details'] as $lDetail) {
                        $loanModel = Loan::find($lDetail['loan_id']);
                        if (!$loanModel) continue;

                        $deduction = (float)$lDetail['installment'];
                        $oldBalance = (float)$loanModel->outstanding_balance;
                        $newBalance = max(0, $oldBalance - $deduction);
                        $newPaidAmount = (float)$loanModel->paid_amount + $deduction;
                        $newPaidInstallments = (int)$loanModel->paid_installments + 1;
                        $nextMonth = ($newBalance > 0) ? Carbon::parse($first_date)->addMonth()->format('Y-m-01') : null;
                        $newStatus = ($newBalance <= 0) ? 'Completed' : 'Active Repayment';

                        \App\Models\LoanRepayment::updateOrCreate(
                            [
                                'loan_id' => $loanModel->id,
                                'payroll_month' => $first_date,
                            ],
                            [
                                'employee_id' => $emp_id,
                                'installment_amount' => $deduction,
                                'principal_paid' => $deduction,
                                'interest_paid' => 0,
                                'remaining_balance' => $newBalance,
                                'amount' => $deduction,
                                'repayment_date' => $first_date,
                                'salary_sheet_reference' => $payrollRecord ? $payrollRecord->id : null,
                                'payroll_batch_id' => 'BATCH-' . $process_month,
                                'created_by' => auth()->check() ? auth()->id() : 1,
                                'remarks' => 'Deducted via payroll for ' . Carbon::parse($first_date)->format('F Y'),
                            ]
                        );

                        $loanModel->update([
                            'outstanding_balance' => $newBalance,
                            'paid_amount' => $newPaidAmount,
                            'paid_installments' => $newPaidInstallments,
                            'last_deduction_month' => $first_date,
                            'next_deduction_month' => $nextMonth,
                            'status' => $newStatus,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing employee {$emp_id} : " . $e->getMessage();
            }
        }

        return [
            'message' => empty($errors) ? 'Successfully Process Done' : 'Process completed with errors',
            'errors' => $errors
        ];
    }

    // loan deduction calculation with effective month and status checks
    public function get_loans_deduction($emp_id, $salary_month = null)
    {
        $query = DB::table('loans')
            ->join('loan_types', 'loan_types.id', '=', 'loans.loan_type_id')
            ->select('loans.*', 'loan_types.name as loan_type_name')
            ->where('loans.employee_id', $emp_id)
            ->whereIn('loans.status', ['Approved', 'Disbursed', 'Active Repayment'])
            ->where('loans.outstanding_balance', '>', 0);

        if ($salary_month) {
            $formattedMonth = Carbon::parse($salary_month)->format('Y-m-01');
            $query->where(function ($q) use ($formattedMonth) {
                $q->whereNull('loans.effective_month')
                  ->orWhere('loans.effective_month', '<=', $formattedMonth);
            });
        }

        $loans = $query->get();

        $result = [
            'Housing Loan' => 0.00,
            'Personal Loan' => 0.00,
            'Motorcycle/Scooter Loan' => 0.00,
            'Other Loan' => 0.00,
            'total_loan_deduction' => 0.00,
            'loan_details' => []
        ];

        foreach ($loans as $loan) {
            $installment = min((float)$loan->monthly_installment, (float)$loan->outstanding_balance);
            if ($installment <= 0) continue;

            $typeName = trim($loan->loan_type_name);
            if (array_key_exists($typeName, $result)) {
                $result[$typeName] += $installment;
            } else {
                $result['Other Loan'] += $installment;
            }

            $result['total_loan_deduction'] += $installment;
            $result['loan_details'][] = [
                'loan_id' => $loan->id,
                'type_name' => $typeName,
                'installment' => $installment,
                'outstanding_balance' => (float)$loan->outstanding_balance
            ];
        }

        return $result;
    }

    // dynamic tax deduction calculation
    function get_tax_deduction($emp_id, $total_gross)
    {
        // 1. Check Employee Tax Profile first
        $profile = \App\Models\EmployeeTaxProfile::where('user_id', $emp_id)->first();
        if ($profile && $profile->monthly_tax_deduction > 0) {
            return (float) $profile->monthly_tax_deduction;
        }

        // 2. Fallback to TaxSetup
        $tax = TaxSetup::where('min_salary', '<=', $total_gross)->where('max_salary', '>=', $total_gross)->first();
        if (!empty($tax)) {
            return (float) $tax->tax_monthly;
        }

        // 3. Fallback to TaxSlabs
        $annualIncome = $total_gross * 12;
        $taxableIncome = max(0, $annualIncome - 350000);
        $slabs = \App\Models\TaxSlab::where('status', 'Active')->orderBy('slab_order', 'asc')->get();
        if ($slabs->count() > 0) {
            $totalTax = 0;
            foreach ($slabs as $slab) {
                if ($taxableIncome > $slab->min_income) {
                    $taxableSegment = min($taxableIncome, $slab->max_income) - $slab->min_income;
                    $totalTax += ($taxableSegment * ($slab->tax_rate / 100)) + $slab->fixed_amount;
                }
            }
            return round($totalTax / 12, 2);
        }

        return 0;
    }

    // dynamic bonus calculation
    function get_bonus_allowance($emp_id, $salary_month, $basic_salary = 0, $gross_salary = 0, $religion = null)
    {
        $month = date('Y-m', strtotime($salary_month));
        
        // 1. Check EmployeeBonus records for explicit paid bonuses
        $employeeBonus = \App\Models\EmployeeBonus::where('user_id', $emp_id)
            ->where('bonus_month', 'like', "{$month}%")
            ->where('payment_status', 'paid')
            ->sum('bonus_amount');

        if ($employeeBonus > 0) {
            return (float) $employeeBonus;
        }

        // 2. Check active BonusSettings
        $bonus = 0;
        $bonusSettings = \App\Models\BonusSetting::where('status', 'Active')
            ->where('bonus_month', 'like', "{$month}%")
            ->get();

        foreach ($bonusSettings as $setting) {
            if (empty($setting->religion) || strtolower($setting->religion) === 'all' || strtolower($setting->religion) === strtolower($religion)) {
                $base = ($setting->calculation_base === 'basic_salary') ? $basic_salary : $gross_salary;
                if ($setting->bonus_type === 'percentage') {
                    $bonus += round(($base * (float)$setting->amount_percentage) / 100, 2);
                } else {
                    $bonus += (float)$setting->amount_percentage;
                }
            }
        }

        return (float) $bonus;
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
