<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSetting extends Model
{
    use HasFactory;

    protected $table = 'bonus_settings';

    protected $fillable = [
        'branch_id',
        'title',
        'bonus_type',
        'calculation_base',
        'amount_percentage',
        'bonus_month',
        'religion',
        'min_service_months',
        'status',
        'remarks',
    ];

    protected $casts = [
        'amount_percentage' => 'float',
        'min_service_months' => 'integer',
        'bonus_month' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function employeeBonuses()
    {
        return $this->hasMany(EmployeeBonus::class, 'bonus_setting_id');
    }
}
