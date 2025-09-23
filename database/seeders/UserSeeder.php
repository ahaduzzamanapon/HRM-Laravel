<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleAndPermission;
use Hash;
use Illuminate\Support\Facades\DB; // Import DB facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();

        $adminUser = User::create([
            'name'  => 'Admin',
            'branch_id' => 1,
            'department_id' => 1,
            'designation_id' => 1,
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Assign Admin role to the admin user
        $adminRole = RoleAndPermission::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminUser->group_id = $adminRole->id;
            $adminUser->save();
        }

        $this->command->info('User account created with following details: admin@admin.com, password');

        $employeeRole = RoleAndPermission::where('name', 'Employee')->first();
        if ($employeeRole) {
            User::factory()->count(10)->create(['group_id' => $employeeRole->id]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
