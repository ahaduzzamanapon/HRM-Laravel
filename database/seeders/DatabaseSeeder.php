<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SiteSettingSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleHasPermissionsSeeder::class);
        $this->call(TrainingDetailSeeder::class);
        $this->call(JobExperienceSeeder::class);
        $this->call(EducationalQualificationSeeder::class);
        $this->call(NomineeInformationSeeder::class);
        $this->call(PromotionDetailSeeder::class);
        $this->call(SalaryIncrementSeeder::class);
        $this->call(TransferDetailSeeder::class);
        $this->call(PersonalDocumentSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(HolydaySeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(ShiftDetailSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
