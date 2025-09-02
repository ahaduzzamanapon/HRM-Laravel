<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Designation::create([
            'desi_name' => 'Software Engineer',
            'desi_status' => 'Active',
        ]);

        Designation::create([
            'desi_name' => 'Project Manager',
            'desi_status' => 'Active',
        ]);

        Designation::create([
            'desi_name' => 'HR Manager',
            'desi_status' => 'Active',
        ]);
    }
}
