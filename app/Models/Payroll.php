<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'branch_id',
        'emp_type',
        'dept_id',
        'desig_id',
        'emp_status',
        'salary_month',
        'n_days',
        'present',
        'absent',
        'leaves',
        'weekend',
        'holiday',
        'pay_day',
        'grade',
        'b_salary',
        'g_salary',
        'pay_salary',
        'h_rent',
        'm_allow',
        'special_allow',
        'child_allow',
        'trans_allow',
        'pf_allow_bank',
        'total_allow',
        'all_allows',
        'gross_salary',
        'absent_deduct',
        'pf_deduct',
        'tax_deduct',
        'bene_deduct',
        'auto_mobile_d',
        'h_loan_deduct',
        'p_loan_deduct',
        'stump_deduct',
        'others_deduct',
        'total_deduct',
        'net_salary',
        'updated_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
