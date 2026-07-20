<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionFormulaRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'name',
        'code',
        'type',
        'expression',
        'description',
        'priority',
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
