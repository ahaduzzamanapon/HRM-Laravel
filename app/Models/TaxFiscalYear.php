<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxFiscalYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function taxSlabs()
    {
        return $this->hasMany(TaxSlab::class, 'fiscal_year_id');
    }
}
