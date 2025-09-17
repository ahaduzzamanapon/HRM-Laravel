<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceTime extends Model
{
    use HasFactory;

    protected $table = 'attendance_time';
    protected $primaryKey = 'time_attendance_id';

    protected $fillable = [
        'employee_id',
        'office_shift_id',
        'attendance_date',
        'clock_in',
        'clock_out',
        'production',
        'ot',
        'late_time',
        'lunch_in',
        'lunch_out',
        'attendance_status',
        'status',
        'late_status',
        'lunch_late_status',
        'early_out_status',
    ];
}
