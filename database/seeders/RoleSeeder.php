<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleAndPermission; // Assuming RoleAndPermission model handles roles

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        RoleAndPermission::create(['name' => 'Admin', 'key' => 'Admin']);
        RoleAndPermission::create(['name' => 'HR', 'key' => 'HR']);
        RoleAndPermission::create(['name' => 'Employee', 'key' => 'Employee']);
    }
}
