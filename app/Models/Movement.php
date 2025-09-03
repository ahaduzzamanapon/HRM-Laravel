<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_location',
        'to_location',
        'distance',
        'reason',
        'ta_amount',
        'da_amount',
        'total_amount',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'ta_amount' => 'decimal:2',
        'da_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
