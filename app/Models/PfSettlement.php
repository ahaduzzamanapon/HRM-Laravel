<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfSettlement extends Model
{
    use HasFactory;

    protected $table = 'pf_settlements';

    protected $fillable = [
        'employee_id',
        'branch_id',
        'total_balance',
        'settlement_amount',
        'reason',
        'status',
        'settlement_date'
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
