<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'scheme_id',
        'contribution_date',
        'employee_contribution',
        'employer_contribution',
        'voluntary_contribution',
        'profit_amount',
        'status',
        'adjustment_note'
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    public function scheme()
    {
        return $this->belongsTo(\App\Models\PfScheme::class, 'scheme_id');
    }

    public function ledgers()
    {
        return $this->morphMany(\App\Models\PfLedger::class, 'reference');
    }

    // Alias so API controllers can eager-load as 'user'
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }
}
