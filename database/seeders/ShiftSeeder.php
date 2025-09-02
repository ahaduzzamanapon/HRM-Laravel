<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\Branch;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = Branch::first(); // Get the first branch, or create one if none exists

        if (!$branch) {
            $branch = Branch::create([
                'branch_name' => 'Default Branch',
                'Address' => 'Default Address',
                'status' => 'Active',
                'description' => 'Default branch for seeding.',
            ]);
        }

        Shift::create([
            'shift_name' => 'Morning Shift',
            'branch_id' => $branch->id,
        ]);

        Shift::create([
            'shift_name' => 'Evening Shift',
            'branch_id' => $branch->id,
        ]);
    }
}
