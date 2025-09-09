<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'value',
        'tax_free_limit',
        'city_specific',
        'city_value',
        'is_active',
    ];

    public function userAllowances()
    {
        return $this->hasMany(UserAllowance::class);
    }
}
