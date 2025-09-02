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
        'password'
    ];

    protected $dates = []; // Laravel will automatically cast 'updated_at'

    // Auto-detect and convert date fields when setting attributes
    public function setAttribute($key, $value)
    {
        if ($this->isDateColumn($key) && !empty($value)) {
            try {
                // Try parsing with expected format
                $value = Carbon::createFromFormat('d-m-Y', trim($value))->format('Y-m-d');
            } catch (\Exception $e) {
                // Log the error for debugging
                \Log::error("Invalid date format for {$key}: {$value}");
            }
        }

        parent::setAttribute($key, $value);
    }

    // public function getAttribute($key)
    // {
    //     $value = parent::getAttribute($key);

    //     if ($this->isDateColumn($key) && !empty($value)) {
    //         try {
    //             return Carbon::parse($value)->format('d-m-Y');
    //         }
    //         catch (\Exception $e) {
    //             return $value; // Return original value if parsing fails
    //         }
    //     }

    //     return $value;
    // }

    private function isDateColumn($key)
    {
        static $dateColumns;

        if (!$dateColumns) {
            $dateColumns = array_filter(Schema::getColumnListing($this->getTable()), function ($column) {
                return in_array(Schema::getColumnType($this->getTable(), $column), ['date', 'datetime', 'timestamp']);
            });
        }

        return in_array($key, $dateColumns);
    }

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
}