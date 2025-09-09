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
        'cat_id',
        'parent_id', // Added
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
        'cat_id' => 'string',
        'parent_id' => 'integer', // Added
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

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
