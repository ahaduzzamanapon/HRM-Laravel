<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'gross_pension',
        'commuted_amount',
        'gratuity',
        'medical_allowance',
        'monthly_pension',
        'net_pension',
        'status',
    ];

    public function profile()
    {
        return $this->belongsTo(PensionProfile::class, 'profile_id');
    }
}
