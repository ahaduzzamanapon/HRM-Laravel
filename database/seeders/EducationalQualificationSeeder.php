<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationalQualification;
use App\Models\User;

class EducationalQualificationSeeder extends Seeder
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

        EducationalQualification::create([
            'user_id' => $user->id,
            'degree' => 'Bachelor of Science in Computer Science',
            'institution' => 'University of Example',
            'passing_year' => '2019',
            'grade' => 'A+',
            'document' => null,
        ]);

        EducationalQualification::create([
            'user_id' => $user->id,
            'degree' => 'Master of Science in Software Engineering',
            'institution' => 'Another University',
            'passing_year' => '2021',
            'grade' => 'A',
            'document' => null,
        ]);
    }
}
