<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTaxProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tin_number',
        'tax_circle',
        'tax_zone',
        'tax_region',
        'filing_status',
        'investment_amount',
        'rebate_claimed',
        'yearly_tax_estimate',
        'monthly_tax_deduction',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
