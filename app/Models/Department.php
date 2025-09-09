<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Department
 * @package App\Models
 * @version September 1, 2025, 11:53 am UTC
 *
 * @property string $name
 * @property string $status
 */
class Department extends Model
{

    public $table = 'departments';
    



    public $fillable = [
        'name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
