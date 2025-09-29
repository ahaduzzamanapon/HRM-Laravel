<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'branch_id' => rand(1, 3),
            'department_id' => rand(1, 3),
            'designation_id' => rand(1, 3),
            'punch_id' => rand(120, 140),
            'shift_id' => 1,
            'date_of_birth' => fake()->date(),
            'date_of_join' => fake()->date(),
            'phone_number' => str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT),
            'basic_salary' => rand(10000, 50000),
            'gross_salary' => rand(10000, 50000),
            'email' => fake()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
