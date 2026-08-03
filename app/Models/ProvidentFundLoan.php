<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'scheme_id',
        'amount',
        'interest_rate',
        'installments',
        'monthly_installment',
        'disbursement_date',
        'next_payment_date',
        'outstanding_balance',
        'status',
        'workflow_status',
        'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function scheme()
    {
        return $this->belongsTo(PfScheme::class, 'scheme_id');
    }

    public function workflows()
    {
        return $this->morphMany(PfApprovalWorkflow::class, 'model');
    }

    public function ledgers()
    {
        return $this->morphMany(PfLedger::class, 'reference');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function repayments()
    {
        return $this->hasMany(ProvidentFundLoanRepayment::class, 'provident_fund_loan_id');
    }
}
