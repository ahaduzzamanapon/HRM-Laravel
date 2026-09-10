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
        'end_date',
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
        'end_date' => 'date',
        'descreption' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'branch_id' => 'nullable',
        'title'    => 'required|string|max:255',
        'date'     => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
