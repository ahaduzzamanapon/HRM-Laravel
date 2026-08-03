<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxSlab extends Model
{
    use HasFactory;

    protected $fillable = [
        'fiscal_year_id',
        'gender_category',
        'min_income',
        'max_income',
        'tax_rate',
        'fixed_amount',
        'slab_order',
        'status',
    ];

    public function fiscalYear()
    {
        return $this->belongsTo(TaxFiscalYear::class, 'fiscal_year_id');
    }
}
