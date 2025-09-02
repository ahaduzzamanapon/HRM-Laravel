<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Branch
 * @package App\Models
 * @version September 1, 2025, 12:02 pm UTC
 *
 * @property string $branch_name
 * @property string $Address
 * @property string $status
 * @property string $description
 */
class Branch extends Model
{

    public $table = 'branchs';
    



    public $fillable = [
        'branch_name',
        'Address',
        'status',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'branch_name' => 'string',
        'Address' => 'string',
        'status' => 'string',
        'description' => 'string'
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

    public function holydays()
    {
        return $this->hasMany(Holyday::class);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
