<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionCommutationFactor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'commutation_rule_id',
        'age',
        'factor',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function rule()
    {
        return $this->belongsTo(PensionCommutationRule::class, 'commutation_rule_id');
    }
}
