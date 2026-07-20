<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionDisbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'disbursement_month',
        'amount',
        'arrear_amount',
        'deductions',
        'net_payable',
        'payment_method',
        'status',
        'bank_reference',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->belongsTo(PensionProfile::class, 'profile_id');
    }
}
