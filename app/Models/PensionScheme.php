<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionScheme extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'code',
        'name',
        'type',
        'employee_contribution_percentage',
        'employer_contribution_percentage',
        'interest_rate',
        'interest_calculation_frequency',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function policy()
    {
        return $this->belongsTo(PensionPolicy::class, 'policy_id');
    }

    public function profiles()
    {
        return $this->hasMany(PensionProfile::class, 'scheme_id');
    }
}
