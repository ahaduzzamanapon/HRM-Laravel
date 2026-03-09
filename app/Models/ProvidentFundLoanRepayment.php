<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundLoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'provident_fund_loan_id',
        'amount',
        'repayment_date',
        'remarks'
    ];

    public function loan()
    {
        return $this->belongsTo(ProvidentFundLoan::class, 'provident_fund_loan_id');
    }
}
