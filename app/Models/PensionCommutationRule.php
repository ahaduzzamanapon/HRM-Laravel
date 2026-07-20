<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionCommutationRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'enabled',
        'max_commutation_percentage',
        'default_commutation_percentage',
        'is_age_based',
        'min_age',
        'max_age',
        'commutation_factor',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function policy()
    {
        return $this->belongsTo(PensionPolicy::class, 'policy_id');
    }

    public function factors()
    {
        return $this->hasMany(PensionCommutationFactor::class, 'commutation_rule_id');
    }
}
