<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobExperience;
use App\Models\User;

class JobExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user, or create one if none exists

        if (!$user) {
            $user = User::factory()->create();
        }

        JobExperience::create([
            'user_id' => $user->id,
            'company_name' => 'ABC Corp',
            'job_title' => 'Software Developer',
            'start_date' => '2020-01-01',
            'end_date' => '2022-12-31',
            'description' => 'Developed and maintained web applications.',
        ]);

        JobExperience::create([
            'user_id' => $user->id,
            'company_name' => 'XYZ Inc.',
            'job_title' => 'Senior Software Engineer',
            'start_date' => '2023-01-01',
            'end_date' => null,
            'description' => 'Led a team of developers and designed new features.',
        ]);
    }
}
