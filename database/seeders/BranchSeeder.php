<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'branch_name' => 'Head Office',
            'Address' => '123 Main Street, City',
            'status' => 'Active',
            'description' => 'Main administrative office.',
        ]);

        Branch::create([
            'branch_name' => 'North Branch',
            'Address' => '456 Oak Avenue, Town',
            'status' => 'Active',
            'description' => 'Branch serving the northern region.',
        ]);

        Branch::create([
            'branch_name' => 'South Branch',
            'Address' => '789 Pine Lane, Village',
            'status' => 'Active',
            'description' => 'Branch serving the southern region.',
        ]);
    }
}
