<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'employee_id',
        'payroll_month',
        'installment_amount',
        'principal_paid',
        'interest_paid',
        'remaining_balance',
        'salary_sheet_reference',
        'payment_date',
        'payroll_batch_id',
        'created_by',
        'amount',
        'repayment_date',
        'remarks',
    ];

    protected $casts = [
        'payroll_month' => 'date',
        'payment_date' => 'date',
        'repayment_date' => 'date',
        'installment_amount' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function loan()
    {
        return $this->belongsTo(\App\Models\Loan::class, 'loan_id');
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
