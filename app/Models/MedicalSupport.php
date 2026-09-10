<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'requested_amount',
        'approved_amount',
        'disbursed_amount',
        'support_date',
        'remarks',
        'status',
        'attachment',
        'approved_by',
        'approved_at',
        'disbursed_by',
        'disbursed_at',
        'disbursement_reference',
        'rejection_reason',
        'admin_remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function attachments()
    {
        return $this->hasMany(WelfareSupportAttachment::class, 'support_id')
            ->where('support_type', 'medical');
    }
}
