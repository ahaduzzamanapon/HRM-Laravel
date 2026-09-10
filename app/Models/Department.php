<?php

namespace App\Models;

use Eloquent as Model;

class Department extends Model
{
    public $table = 'departments';

    public $fillable = [
        'name',
        'branch_id',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'branch_id' => 'integer',
        'name' => 'string',
        'status' => 'string'
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function designations()
    {
        return $this->hasMany(Designation::class, 'department_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
