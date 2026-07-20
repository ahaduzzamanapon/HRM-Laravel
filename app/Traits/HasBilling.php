<?php

namespace App\Traits;

use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasBilling
{
    /**
     * Get the bills associated with the user.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Get the payments associated with the user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
