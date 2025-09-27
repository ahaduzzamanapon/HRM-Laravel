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

        $salaryGrades = \App\Models\SalaryGrade::pluck('id')->toArray();

        User::truncate();

        $adminUser = User::create([
            'name'  => 'Admin',
            'branch_id' => 1,
            'department_id' => 1,
            'designation_id' => 1,
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'salary_grade_id' => $salaryGrades[array_rand($salaryGrades)],
        ]);
        $adminUser->punch_id = 'PUNCH-' . $adminUser->id;
        $adminUser->emp_id = 'EMP-' . $adminUser->id;
        $adminUser->save();

        // Assign Admin role to the admin user
        $adminRole = RoleAndPermission::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminUser->group_id = $adminRole->id;
            $adminUser->save();
        }

        $this->command->info('User account created with following details: admin@admin.com, password');

        $employeeRole = RoleAndPermission::where('name', 'Employee')->first();
        if ($employeeRole) {
            User::factory()->count(10)->create([
                'group_id' => $employeeRole->id,
                'salary_grade_id' => $salaryGrades[array_rand($salaryGrades)],
            ])->each(function ($user) {
                $user->punch_id = 'PUNCH-' . $user->id;
                $user->emp_id = 'EMP-' . $user->id;
                $user->save();
            });
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
