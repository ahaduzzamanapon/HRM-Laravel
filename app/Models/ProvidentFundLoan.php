<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'interest_rate',
        'installments',
        'monthly_installment',
        'disbursement_date',
        'next_payment_date',
        'outstanding_balance',
        'status',
        'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function repayments()
    {
        return $this->hasMany(ProvidentFundLoanRepayment::class, 'provident_fund_loan_id');
    }
}
