<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'location', 'status', 'deadline'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Scope for active jobs shown to candidates
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->where(function ($q) {
                         $q->whereNull('deadline')
                           ->orWhere('deadline', '>=', now()->toDateString());
                     });
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
