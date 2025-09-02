<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveType::create([
            'name' => 'Annual Leave',
            'total_days_per_year' => 15,
            'gender_criteria' => 'All',
        ]);

        LeaveType::create([
            'name' => 'Sick Leave',
            'total_days_per_year' => 10,
            'gender_criteria' => 'All',
        ]);

        LeaveType::create([
            'name' => 'Maternity Leave',
            'total_days_per_year' => 90,
            'gender_criteria' => 'Female',
        ]);

        LeaveType::create([
            'name' => 'Paternity Leave',
            'total_days_per_year' => 5,
            'gender_criteria' => 'Male',
        ]);
    }
}
