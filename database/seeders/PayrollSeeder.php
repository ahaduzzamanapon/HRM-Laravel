<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payroll;
use Carbon\Carbon;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::take(5)->get();

        foreach ($users as $user) {
            $n_days = Carbon::now()->daysInMonth;
            $present = rand(20, $n_days);
            $absent = $n_days - $present;
            $b_salary = $user->salary;
            $pay_salary = ($b_salary / $n_days) * $present;
            $total_allow = rand(1000, 5000);
            $total_deduct = rand(500, 2000);
            $net_salary = $pay_salary + $total_allow - $total_deduct;

            Payroll::create([
                'user_id' => $user->id,
                'emp_status' => 1,
                'dept_id' => $user->department_id,
                'desig_id' => $user->designation_id,
                'salary_month' => Carbon::now()->format('Y-m-d'),
                'n_days' => $n_days,
                'present' => $present,
                'absent' => $absent,
                'leave' => 0,
                'weekend' => 0,
                'holiday' => 0,
                'pay_day' => $present,
                'b_salary' => $b_salary,
                'g_salary' => $b_salary,
                'pay_salary' => $pay_salary,
                'total_allow' => $total_allow,
                'all_allows' => '[]',
                'absent_deduct' => 0,
                'loan_deduct' => 0,
                'pf_deduct' => 0,
                'others_deduct' => 0,
                'total_deduct' => $total_deduct,
                'net_salary' => $net_salary,
            ]);
        }
    }
}
