<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class RoleAndPermission
 * @package App\Models
 * @version January 20, 2025, 4:58 am UTC
 *
 * @property string $name
 * @property string $key
 */
class RoleAndPermission extends Model
{

    public $table = 'roles';
    



    public $fillable = [
        'name',
        'key'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'key' => 'string'
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

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roll_has', 'roll_id', 'permission_id');
    }
}