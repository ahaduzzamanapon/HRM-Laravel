<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'employment_type',
        'experience_level',
        'salary_range_start',
        'salary_range_end',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_range_start' => 'decimal:2',
        'salary_range_end' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recruitment) {
            if (empty($recruitment->slug)) {
                $recruitment->slug = Str::slug($recruitment->title);
            }
        });

        static::updating(function ($recruitment) {
            if (empty($recruitment->slug)) {
                $recruitment->slug = Str::slug($recruitment->title);
            }
        });
    }
}
