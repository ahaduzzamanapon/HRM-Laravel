<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricCommand extends Model
{
    use HasFactory;

    protected $fillable = [
        'biometric_device_id',
        'user_id',
        'command_type',
        'command_string',
        'status',
        'sent_at',
        'executed_at',
    ];

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'biometric_device_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
