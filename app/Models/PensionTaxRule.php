<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionTaxRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'taxable',
        'tax_method',
        'tax_percentage',
        'min_exemption',
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
