<?php

namespace App\Models;

use Eloquent as Model;

class Designation extends Model
{
    public $table = 'designations';

    public $fillable = [
        'desi_name',
        'branch_id',
        'department_id',
        'desi_status'
    ];

    protected $casts = [
        'id' => 'integer',
        'branch_id' => 'integer',
        'department_id' => 'integer',
        'desi_name' => 'string',
        'desi_status' => 'string'
    ];

    public static $rules = [
        'desi_name' => 'required',
        'desi_status' => 'required'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'designation_id');
    }
}
