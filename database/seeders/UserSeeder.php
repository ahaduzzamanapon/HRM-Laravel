<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleAndPermission;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        // Truncate the users table
        User::truncate();

        // Create Admin User
        $adminUser = User::create([
            'name'  => 'Admin',
            'branch_id' => 1,
            'department_id' => 1,
            'designation_id' => 1,
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->emp_id = 'EMP-' . $adminUser->id;
        $adminUser->punch_id = 'PUNCH-' . $adminUser->id;
        $adminUser->save();

        $adminRole = RoleAndPermission::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminUser->group_id = $adminRole->id;
            $adminUser->save();
        }



        // Fetch users from API
        $response = Http::get('http://118.179.191.20/hr_payroll/Command/get_emp/200');
        $users = $response->json();

        $employeeRole = RoleAndPermission::where('name', 'Employee')->first();
        $branches = Branch::pluck('id')->toArray();
        $departments = Department::pluck('id')->toArray();
        $designations = Designation::pluck('id')->toArray();
        $shifts = \App\Models\Shift::pluck('id')->toArray();
        $salaryGrades = \App\Models\SalaryGrade::pluck('id')->toArray();

        foreach ($users as $userData) {
            $imageUrl = 'http://118.179.191.20/hr_payroll/uploads/photo/' . $userData['img_source'];
            $imageContents = @file_get_contents($imageUrl);
            $imageName = basename($userData['img_source']);
            $imagePath = 'public/user_images/' . $imageName;
            Storage::put($imagePath, $imageContents);
            $name = $userData['name_en'];
            $nameParts = explode(' ', trim($name));
            $firstName = count($nameParts) === 1 ? $nameParts[0] : implode(' ', array_slice($nameParts, 0, 2));
            $lastName = count($nameParts) === 1 ? $nameParts[0] : implode(' ', array_slice($nameParts, 2));

            $user = User::create([
                'name' => $firstName,
                'last_name' => $lastName,
                'email' => $userData['emp_id'] . '@example.com',
                'password' => Hash::make('password'),
                'designation_id' => $designations[array_rand($designations)],
                'date_of_birth' => $userData['emp_dob'],
                'date_of_join' => $userData['emp_join_date'],
                'gender' => $userData['gender'],
                'address' => $userData['per_village'],
                'phone_number' => $this->banglaToEnglishNumber($userData['personal_mobile']),
                'image' => $imagePath,
                'group_id' => $employeeRole ? $employeeRole->id : null,
                'blood_group' => $userData['blood'],
                'religion' => $userData['religion'],
                'marital_status' => $userData['marital_status'],
                'punch_id' => $userData['emp_id'],
                'emp_id' => $userData['emp_id'],
                'branch_id' => $branches[array_rand($branches)],
                'department_id' => $departments[array_rand($departments)],
                'shift_id' => $shifts[array_rand($shifts)],
                'salary_grade_id' => $salaryGrades[array_rand($salaryGrades)],
            ]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_key_CHECKS=1;');
    }
    function banglaToEnglishNumber($number)
{
    $banglaDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $englishDigits = ['0','1','2','3','4','5','6','7','8','9'];
    
    return str_replace($banglaDigits, $englishDigits, $number);
}
}