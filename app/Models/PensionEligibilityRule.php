<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionEligibilityRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'min_service_years',
        'max_service_years',
        'min_age',
        'max_age',
        'employment_type',
        'employee_grade',
        'department_id',
        'designation_id',
        'gender',
        'is_permanent',
        'is_confirmed',
        'eligible_for_gratuity',
        'eligible_for_family_pension',
        'eligible_for_commutation',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function policy()
    {
        return $this->belongsTo(PensionPolicy::class, 'policy_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
