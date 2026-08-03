<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'pf_withdrawals';

    protected $fillable = [
        'employee_id',
        'branch_id',
        'amount',
        'reason',
        'status',
        'disbursement_date',
        'rejection_reason'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function workflows()
    {
        return $this->morphMany(PfApprovalWorkflow::class, 'model');
    }

    public function ledgers()
    {
        return $this->morphMany(PfLedger::class, 'reference');
    }
}
