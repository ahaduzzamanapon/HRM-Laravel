<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'scheme_id',
        'retirement_date',
        'retirement_type',
        'qualifying_service_years',
        'qualifying_service_months',
        'last_basic_pay',
        'eligibility_status',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departure()
    {
        return $this->hasOne(EmployeeDeparture::class, 'user_id', 'user_id');
    }

    public function scheme()
    {
        return $this->belongsTo(PensionScheme::class, 'scheme_id');
    }

    public function eligibilityCheck()
    {
        return $this->hasOne(PensionEligibilityCheck::class, 'profile_id');
    }

    public function calculation()
    {
        return $this->hasOne(PensionCalculation::class, 'profile_id');
    }

    public function disbursements()
    {
        return $this->hasMany(PensionDisbursement::class, 'profile_id');
    }
    
    public function workflows()
    {
        return $this->morphMany(PensionWorkflow::class, 'trackable');
    }
}
