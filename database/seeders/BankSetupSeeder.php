<?php

namespace Database\Seeders;

use App\Models\BankSetup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankSetup::create([
            'bank_name' => 'Sonali Bank',
            'branch_name' => 'Principal Branch',
            'address' => 'Dhaka',
            'bank_code' => 'SB001',
            'description' => 'Sonali Bank PLC'
        ]);

        BankSetup::create([
            'bank_name' => 'Janata Bank',
            'branch_name' => 'Main Branch',
            'address' => 'Dhaka',
            'bank_code' => 'JB001',
            'description' => 'Janata Bank PLC'
        ]);

        BankSetup::create([
            'bank_name' => 'Agrani Bank',
            'branch_name' => 'Head Office Branch',
            'address' => 'Dhaka',
            'bank_code' => 'AB001',
            'description' => 'Agrani Bank PLC'
        ]);
    }
}
