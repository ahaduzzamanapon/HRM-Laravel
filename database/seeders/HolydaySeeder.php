<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Holyday;
use App\Models\Branch;

class HolydaySeeder extends Seeder
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

        Holyday::create([
            'branch_id' => $branch->id,
            'title' => 'New Year\'s Day',
            'status' => 'Published',
            'date' => '2025-01-01',
            'descreption' => 'Public holiday for New Year.',
        ]);

        Holyday::create([
            'branch_id' => $branch->id,
            'title' => 'Independence Day',
            'status' => 'Published',
            'date' => '2025-03-26',
            'descreption' => 'National Independence Day.',
        ]);
    }
}
