<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'name' => 'Human Resources',
            'status' => 'Active',
        ]);

        Department::create([
            'name' => 'Software Development',
            'status' => 'Active',
        ]);

        Department::create([
            'name' => 'Marketing',
            'status' => 'Active',
        ]);
    }
}
