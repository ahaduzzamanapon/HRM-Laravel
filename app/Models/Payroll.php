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
        'emp_status',
        'dept_id',
        'desig_id',
        'salary_month',
        'n_days',
        'present',
        'absent',
        'leave',
        'weekend',
        'holiday',
        'pay_day',
        'b_salary',
        'g_salary',
        'pay_salary',
        'total_allow',
        'all_allows',
        'absent_deduct',
        'loan_deduct',
        'pf_deduct',
        'others_deduct',
        'total_deduct',
        'net_salary',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
