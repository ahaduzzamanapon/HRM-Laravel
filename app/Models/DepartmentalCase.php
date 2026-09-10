<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentalCase extends Model
{
    use HasFactory;

    protected $table = 'departmental_cases';

    protected $fillable = [
        'case_no',
        'employee_id',
        'incident_date',
        'allegation_type',
        'allegation_category',
        'status',
        'disciplinary_issue_details',
        'document',
        'show_cause_date',
        'show_cause_explanation',
        'committee_comments',
        'penalty_id',
        'penalty_amount',
        'final_action_taken',
        'notified_at',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'show_cause_date' => 'date',
        'notified_at' => 'datetime',
        'penalty_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->case_no)) {
                $year = date('Y');
                $latest = static::whereYear('created_at', $year)->latest('id')->first();
                $seq = $latest ? ($latest->id + 1) : 1;
                $model->case_no = 'DC-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            }
            if (empty($model->status)) {
                $model->status = 'Pending';
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function penalty()
    {
        return $this->belongsTo(\App\Models\Penalty::class, 'penalty_id');
    }

    /**
     * Get badge color class based on status.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'Pending' => 'bg-secondary',
            'Under Investigation' => 'bg-info text-dark',
            'Show Cause Issued' => 'bg-warning text-dark',
            'Hearing Scheduled' => 'bg-primary',
            'Penalty Imposed' => 'bg-danger',
            'Dismissed' => 'bg-dark',
            'Closed' => 'bg-success',
            default => 'bg-secondary',
        };
    }
}
