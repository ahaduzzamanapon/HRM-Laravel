<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromotionDetail;
use App\Models\User;

class PromotionDetailSeeder extends Seeder
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

        PromotionDetail::create([
            'user_id' => $user->id,
            'promotion_date' => '2022-05-01',
            'new_designation' => 'Senior Software Engineer',
            'old_designation' => 'Software Engineer',
            'pay_grade_change' => true,
            'new_salary' => 75000.00,
            'document' => null,
        ]);

        PromotionDetail::create([
            'user_id' => $user->id,
            'promotion_date' => '2024-01-01',
            'new_designation' => 'Lead Software Engineer',
            'old_designation' => 'Senior Software Engineer',
            'pay_grade_change' => true,
            'new_salary' => 90000.00,
            'document' => null,
        ]);
    }
}
