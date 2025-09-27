<?php

namespace Database\Seeders;

use App\Models\SalaryGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaryGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalaryGrade::create([
            'grade' => 'Grade 5',
            'starting_salary' => 30000,
            'end_salary' => 50000,
            'description' => 'Grade A'
        ]);

        SalaryGrade::create([
            'grade' => 'Grade 4',
            'starting_salary' => 50001,
            'end_salary' => 70000,
            'description' => 'Grade B'
        ]);

        SalaryGrade::create([
            'grade' => 'Grade 3',
            'starting_salary' => 70001,
            'end_salary' => 90000,
            'description' => 'Grade C'
        ]);

        SalaryGrade::create([
            'grade' => 'Grade 2',
            'starting_salary' => 90001,
            'end_salary' => 120000,
            'description' => 'Grade D'
        ]);

        SalaryGrade::create([
            'grade' => 'Grade 1',
            'starting_salary' => 120001,
            'end_salary' => 150000,
            'description' => 'Grade E'
        ]);
    }
}
