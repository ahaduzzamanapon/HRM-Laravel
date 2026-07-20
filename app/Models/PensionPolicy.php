<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionPolicy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'effective_from',
        'effective_to',
        'retirement_age',
        'min_service_years',
        'max_service_years',
        'early_retirement_allowed',
        'voluntary_retirement_allowed',
        'calculation_base',
        'max_pension_percentage',
        'min_pension_amount',
        'max_pension_amount',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function schemes()
    {
        return $this->hasMany(PensionScheme::class, 'policy_id');
    }

    public function eligibilityRule()
    {
        return $this->hasOne(PensionEligibilityRule::class, 'policy_id');
    }

    public function formulaRules()
    {
        return $this->hasMany(PensionFormulaRule::class, 'policy_id');
    }

    public function commutationRule()
    {
        return $this->hasOne(PensionCommutationRule::class, 'policy_id');
    }

    public function gratuityRule()
    {
        return $this->hasOne(PensionGratuityRule::class, 'policy_id');
    }

    public function medicalRule()
    {
        return $this->hasOne(PensionMedicalRule::class, 'policy_id');
    }

    public function familyRule()
    {
        return $this->hasOne(PensionFamilyRule::class, 'policy_id');
    }

    public function revisionRules()
    {
        return $this->hasMany(PensionRevisionRule::class, 'policy_id');
    }

    public function taxRule()
    {
        return $this->hasOne(PensionTaxRule::class, 'policy_id');
    }

    public function paymentRule()
    {
        return $this->hasOne(PensionPaymentRule::class, 'policy_id');
    }

    public function versions()
    {
        return $this->hasMany(PensionPolicyVersion::class, 'policy_id');
    }
}
