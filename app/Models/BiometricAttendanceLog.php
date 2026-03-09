<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricAttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'biometric_device_id',
        'biometric_user_id',
        'timestamp',
        'status_code',
        'verify_mode',
    ];

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'biometric_device_id');
    }
}
