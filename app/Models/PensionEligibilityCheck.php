<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionEligibilityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'age_validated',
        'service_years_validated',
        'documents_verified',
        'no_disciplinary_cases',
        'lwp_impact_details',
        'overall_status',
        'checked_by',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'age_validated' => 'boolean',
        'service_years_validated' => 'boolean',
        'documents_verified' => 'boolean',
        'no_disciplinary_cases' => 'boolean',
    ];

    public function profile()
    {
        return $this->belongsTo(PensionProfile::class, 'profile_id');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
