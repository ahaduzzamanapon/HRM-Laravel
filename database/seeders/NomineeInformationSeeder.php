<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NomineeInformation;
use App\Models\User;

class NomineeInformationSeeder extends Seeder
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

        NomineeInformation::create([
            'user_id' => $user->id,
            'nominee_name' => 'Jane Doe',
            'relation' => 'Spouse',
            'voter_id' => '1234567890',
            'photo' => null,
            'percentage' => 100.00,
        ]);

        NomineeInformation::create([
            'user_id' => $user->id,
            'nominee_name' => 'John Doe Jr.',
            'relation' => 'Child',
            'voter_id' => '0987654321',
            'photo' => null,
            'percentage' => 50.00,
        ]);
    }
}
