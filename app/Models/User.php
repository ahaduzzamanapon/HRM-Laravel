<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Models\TrainingDetail; // Import the TrainingDetail model
use App\Models\JobExperience; // Import the JobExperience model
use App\Models\EducationalQualification; // Import the EducationalQualification model
use App\Models\NomineeInformation; // Import the NomineeInformation model
use App\Models\PromotionDetail; // Import the PromotionDetail model
use App\Models\SalaryIncrement; // Import the SalaryIncrement model
use App\Models\TransferDetail; // Import the TransferDetail model
use App\Models\PersonalDocument; // Import the PersonalDocument model
use App\Models\Branch; // Import the Branch model
use App\Models\UserAllowance; // Import the UserAllowance model
use App\Models\RoleAndPermission; // Import the RoleAndPermission model
use App\Models\Designation; // Import the Designation model
use App\Models\Shift; // Import the Shift model


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'designation_id',
        'branch_id',
        'department_id', // Added
        'shift_id',
        'date_of_birth',
        'date_of_join',
        'gender',
        'address',
        'phone_number',
        'image',
        'group_id',
        'blood_group',
        'religion',
        'marital_status',
        'punch_id',
        'password',
        'basic_salary',
        'gross_salary',
        'bank_id',
        'account_no',
        'salary_grade_id',
        'emp_type',
        'emp_id',
        'status'
    ];


    public function trainingDetails()
    {
        return $this->hasMany(TrainingDetail::class);
    }

    public function jobExperiences()
    {
        return $this->hasMany(JobExperience::class);
    }

    public function educationalQualifications()
    {
        return $this->hasMany(EducationalQualification::class);
    }

    public function nomineeInformation()
    {
        return $this->hasMany(NomineeInformation::class);
    }

    public function promotionDetails()
    {
        return $this->hasMany(PromotionDetail::class);
    }

    public function salaryIncrements()
    {
        return $this->hasMany(SalaryIncrement::class);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function personalDocuments()
    {
        return $this->hasMany(PersonalDocument::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(RoleAndPermission::class, 'group_id');
    }

    public function userAllowances()
    {
        return $this->hasMany(UserAllowance::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function childAllowances()
    {
        return $this->hasMany(ChildAllowance::class);
    }
}
