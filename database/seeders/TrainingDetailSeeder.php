<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingDetail;
use App\Models\User;

class TrainingDetailSeeder extends Seeder
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

        TrainingDetail::create([
            'user_id' => $user->id,
            'training_name' => 'Laravel Development',
            'training_provider' => 'Laracasts',
            'training_type' => 'Domestic',
            'start_date' => '2023-01-15',
            'end_date' => '2023-01-20',
            'description' => 'Comprehensive Laravel training.',
            'document' => null,
        ]);

        TrainingDetail::create([
            'user_id' => $user->id,
            'training_name' => 'Vue.js Fundamentals',
            'training_provider' => 'Vue Mastery',
            'training_type' => 'Foreign',
            'start_date' => '2023-03-10',
            'end_date' => '2023-03-15',
            'description' => 'Mastering Vue.js basics.',
            'document' => null,
        ]);
    }
}
