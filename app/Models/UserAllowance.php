<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'allowance_setting_id',
        'is_enabled',
        'custom_value',
    ];

    public function allowanceSetting()
    {
        return $this->belongsTo(AllowanceSetting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
