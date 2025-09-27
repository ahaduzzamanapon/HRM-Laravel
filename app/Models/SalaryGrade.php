<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class SalaryGrade
 * @package App\Models
 * @version September 27, 2025, 5:12 am UTC
 *
 * @property string $grade
 * @property integer $starting_salary
 * @property integer $end_salary
 * @property string $description
 */
class SalaryGrade extends Model
{

    public $table = 'salary_grades';
    



    public $fillable = [
        'grade',
        'starting_salary',
        'end_salary',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'grade' => 'string',
        'starting_salary' => 'integer',
        'end_salary' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'grade' => 'required',
        'starting_salary' => 'required|numeric',
        'end_salary' => 'required|numeric|gte:starting_salary',
        'description' => 'nullable'
    ];

    
}
