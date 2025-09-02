<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Holyday
 * @package App\Models
 * @version September 1, 2025, 12:19 pm UTC
 *
 * @property integer $branch_id
 * @property string $title
 * @property string $status
 * @property string $date
 * @property string $descreption
 */
class Holyday extends Model
{

    public $table = 'holydays';
    



    public $fillable = [
        'branch_id',
        'title',
        'status',
        'date',
        'descreption'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'branch_id' => 'integer',
        'title' => 'string',
        'status' => 'string',
        'date' => 'date',
        'descreption' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
