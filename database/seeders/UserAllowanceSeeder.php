<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AllowanceSetting;
use App\Models\UserAllowance;
use Illuminate\Database\Seeder;

class UserAllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $allowanceSettings = AllowanceSetting::all();

        foreach ($users as $user) {
            foreach ($allowanceSettings as $allowanceSetting) {
                UserAllowance::create([
                    'user_id' => $user->id,
                    'allowance_setting_id' => $allowanceSetting->id,
                    'is_enabled' => true,
                    'custom_value' => null,
                ]);
            }
        }
    }
}
