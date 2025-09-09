<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'loan_type_id',
        'amount',
        'interest_rate',
        'installments',
        'monthly_installment',
        'disbursement_date',
        'next_payment_date',
        'outstanding_balance',
        'status',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function loanType()
    {
        return $this->belongsTo(\App\Models\LoanType::class, 'loan_type_id');
    }

    public function loanRepayments()
    {
        return $this->hasMany(\App\Models\LoanRepayment::class, 'loan_id');
    }
}
