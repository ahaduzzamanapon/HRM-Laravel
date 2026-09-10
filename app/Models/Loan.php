<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_no',
        'employee_id',
        'branch_id',
        'loan_type_id',
        'amount',
        'interest_rate',
        'installments',
        'paid_installments',
        'monthly_installment',
        'disbursement_date',
        'effective_month',
        'required_month',
        'next_payment_date',
        'outstanding_balance',
        'paid_amount',
        'last_deduction_month',
        'next_deduction_month',
        'payment_method',
        'bank_account_info',
        'voucher_no',
        'status',
        'remarks',
    ];

    protected $casts = [
        'disbursement_date' => 'date',
        'effective_month' => 'date',
        'required_month' => 'date',
        'next_payment_date' => 'date',
        'last_deduction_month' => 'date',
        'next_deduction_month' => 'date',
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    public function loanType()
    {
        return $this->belongsTo(\App\Models\LoanType::class, 'loan_type_id');
    }

    public function loanRepayments()
    {
        return $this->hasMany(\App\Models\LoanRepayment::class, 'loan_id');
    }

    public function loanSchedules()
    {
        return $this->hasMany(\App\Models\LoanSchedule::class, 'loan_id');
    }

    public function loanApprovals()
    {
        return $this->hasMany(\App\Models\LoanApproval::class, 'loan_id');
    }
}
