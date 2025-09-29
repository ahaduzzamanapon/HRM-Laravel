<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payroll;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Payroll::create([
                'user_id' => $user->id,
                'branch_id' => $user->branch_id ?? 1,
                'emp_type' => 1, // Assuming 1 for full-time
                'dept_id' => $user->department_id,
                'desig_id' => $user->designation_id,
                'emp_status' => 1, // Assuming 1 for active
                'salary_month' => now()->format('Y-m-01'),
                'n_days' => 30,
                'present' => 28,
                'absent' => 2,
                'leaves' => 0,
                'weekend' => 8,
                'holiday' => 1,
                'pay_day' => 22,
                'grade' => 1,
                'b_salary' => 50000.00,
                'g_salary' => 60000.00,
                'pay_salary' => 55000.00,
                'h_rent' => 10000.00,
                'm_allow' => 2000.00,
                'f_allow' => 200.00,
                'special_allow' => 1000.00,
                'child_allow' => 0.00,
                'trans_allow' => 1000.00,
                'pf_allow_bank' => 2000.00,
                'total_allow' => 16000.00,
                'all_allows' => '[]',
                'gross_salary' => 71000.00,
                'absent_deduct' => 2000.00,
                'pf_deduct' => 2000.00,
                'tax_deduct' => 1000.00,
                'bene_deduct' => 0.00,
                'auto_mobile_d' => 0.00,
                'h_loan_deduct' => 0.00,
                'p_loan_deduct' => 0.00,
                'stump_deduct' => 100.00,
                'others_deduct' => 0.00,
                'total_deduct' => 5100.00,
                'net_salary' => 65900.00,
                'updated_by' => 1,
            ]);
        }
    }
}
