<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $fillable = [
        'user_id',
        'billing_period',
        'base_amount',
        'arrear_amount',
        'total_amount',
        'paid_amount',
        'status',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'arrear_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the bill.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payments associated with this bill.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
