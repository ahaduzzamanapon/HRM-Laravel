<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalaryIncrement;
use App\Models\User;

class SalaryIncrementSeeder extends Seeder
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

        SalaryIncrement::create([
            'user_id' => $user->id,
            'increment_date' => '2022-01-01',
            'old_salary' => 50000.00,
            'new_salary' => 55000.00,
            'increment_amount' => 5000.00,
            'document' => null,
        ]);

        SalaryIncrement::create([
            'user_id' => $user->id,
            'increment_date' => '2023-01-01',
            'old_salary' => 55000.00,
            'new_salary' => 60000.00,
            'increment_amount' => 5000.00,
            'document' => null,
        ]);
    }
}
