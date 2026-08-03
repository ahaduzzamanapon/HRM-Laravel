<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBonus extends Model
{
    use HasFactory;

    protected $table = 'employee_bonuses';

    protected $fillable = [
        'bonus_setting_id',
        'user_id',
        'branch_id',
        'bonus_month',
        'base_amount',
        'bonus_amount',
        'payment_status',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'base_amount' => 'float',
        'bonus_amount' => 'float',
        'bonus_month' => 'date',
        'payment_date' => 'date',
    ];

    public function bonusSetting()
    {
        return $this->belongsTo(BonusSetting::class, 'bonus_setting_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
