<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AllowanceSetting;

class AllowanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AllowanceSetting::create([
            'name' => 'House Rent',
            'type' => 'percentage',
            'value' => 10,
            'tax_free_limit' => 25000,
            'is_active' => true,
        ]);

        AllowanceSetting::create([
            'name' => 'Medical Allowance',
            'type' => 'fixed',
            'value' => 1500,
            'tax_free_limit' => 120000,
            'is_active' => true,
        ]);

        AllowanceSetting::create([
            'name' => 'Transport Allowance',
            'type' => 'fixed',
            'value' => 1000,
            'tax_free_limit' => 30000,
            'is_active' => true,
        ]);
    }
}