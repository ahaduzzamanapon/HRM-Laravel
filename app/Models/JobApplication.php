<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'recruitment_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'resume_path',
        'cover_letter',
        'status'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class);
    }
}
