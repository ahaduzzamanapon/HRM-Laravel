<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionFamilyRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'enabled',
        'spouse_percentage',
        'child_percentage',
        'max_children',
        'max_child_age',
        'disabled_child_lifetime_support',
        'widow_lifetime_support',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function policy()
    {
        return $this->belongsTo(PensionPolicy::class, 'policy_id');
    }
}
