<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelfareFundSetting extends Model
{
    use HasFactory;

    protected $table = 'welfare_fund_settings';

    protected $fillable = [
        'welfare_fund_enabled',
        'deduction_type',
        'deduction_policy',
        'company_contribution_enabled',
        'company_contribution_type',
        'company_contribution_amount',
        'max_medical_limit',
        'max_funeral_limit',
        'max_education_limit',
        'allow_negative_balance',
        'require_attachment',
    ];

    protected $casts = [
        'welfare_fund_enabled' => 'boolean',
        'company_contribution_enabled' => 'boolean',
        'allow_negative_balance' => 'boolean',
        'require_attachment' => 'boolean',
        'company_contribution_amount' => 'decimal:2',
        'max_medical_limit' => 'decimal:2',
        'max_funeral_limit' => 'decimal:2',
        'max_education_limit' => 'decimal:2',
    ];

    public static function instance()
    {
        return self::first() ?? self::create([
            'welfare_fund_enabled' => true,
            'deduction_type' => 'fixed',
            'deduction_policy' => 'compulsory',
            'company_contribution_enabled' => false,
            'company_contribution_type' => 'matching_percentage',
            'company_contribution_amount' => 100.00,
            'max_medical_limit' => 50000.00,
            'max_funeral_limit' => 30000.00,
            'max_education_limit' => 25000.00,
            'allow_negative_balance' => false,
            'require_attachment' => true,
        ]);
    }
}
