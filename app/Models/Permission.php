<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Permission
 * @package App\Models
 * @version January 20, 2025, 3:59 am UTC
 *
 * @property string $name
 * @property string $key
 * @property string $cat_id
 */
class Permission extends Model
{

    public $table = 'permissions';
    



    public $fillable = [
        'name',
        'key',
        'cat_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'key' => 'string',
        'cat_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'key' => 'required'
    ];

    
}
