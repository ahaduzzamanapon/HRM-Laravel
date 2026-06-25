<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\Department;
use Carbon\Carbon;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // 1. Setup: Create Asset Categories
        $categories = [
            ['name' => 'Laptops', 'description' => 'Company provided laptops', 'status' => 1],
            ['name' => 'Desktops', 'description' => 'Office desktop workstations', 'status' => 1],
            ['name' => 'Monitors', 'description' => 'External display monitors', 'status' => 1],
            ['name' => 'Furniture', 'description' => 'Office chairs, desks, etc.', 'status' => 1],
            ['name' => 'Networking', 'description' => 'Routers, Switches, Access Points', 'status' => 1],
        ];

        foreach ($categories as $catData) {
            AssetCategory::updateOrCreate(['name' => $catData['name']], $catData);
        }

        $categoryIds = AssetCategory::pluck('id')->toArray();
        $departmentIds = Department::pluck('id')->toArray();

        // 2. Generate 20 Dummy Assets, Assignments, and Logs
        $statuses = ['Available', 'Assigned', 'Maintenance', 'Retired', 'Disposed'];
        $brands = ['Dell', 'HP', 'Lenovo', 'Apple', 'Asus', 'Acer', 'IKEA', 'Cisco'];
        
        $userIds = \App\Models\User::pluck('id')->toArray();
        if (empty($userIds)) {
            $userIds = [1]; // Fallback to 1 if no users exist
        }

        for ($i = 1; $i <= 20; $i++) {
            $purchaseDate = $faker->dateTimeBetween('-3 years', '-1 month');
            $warrantyExpiry = Carbon::parse($purchaseDate)->addYears(rand(1, 3));
            
            // Generate Asset Code
            $assetCode = 'AST-' . strtoupper($faker->unique()->lexify('????')) . '-' . $faker->unique()->numerify('####');
            
            // Randomly select a category
            $categoryId = $faker->randomElement($categoryIds);
            $categoryName = AssetCategory::find($categoryId)->name ?? 'Other';
            
            // Name based on category
            $name = '';
            $brand = $faker->randomElement($brands);
            $model = '';
            
            if ($categoryName == 'Laptops' || $categoryName == 'Desktops') {
                $name = $brand . ' Workstation ' . $faker->numerify('Pro ####');
                $model = $faker->lexify('XPS ????');
            } elseif ($categoryName == 'Monitors') {
                $name = $brand . ' ' . $faker->numberBetween(21, 32) . ' inch Display';
                $model = $faker->lexify('UL????');
            } elseif ($categoryName == 'Furniture') {
                $name = 'Ergonomic Office Chair';
                $brand = 'IKEA';
                $model = 'MARKUS';
            } else {
                $name = 'Cisco Access Point';
                $brand = 'Cisco';
                $model = 'Meraki MR33';
            }

            // Determine an initial status and current status
            $currentStatus = $faker->randomElement($statuses);

            // Create the Asset
            $asset = Asset::create([
                'category_id'          => $categoryId,
                'department_id'        => !empty($departmentIds) ? $faker->randomElement($departmentIds) : null,
                'asset_code'           => $assetCode,
                'name'                 => $name,
                'brand'                => $brand,
                'model'                => $model,
                'serial_number'        => strtoupper($faker->unique()->bothify('SN-????-####-????')),
                'purchase_date'        => $purchaseDate,
                'purchase_cost'        => $faker->randomFloat(2, 100, 2500),
                'warranty_expiry_date' => $warrantyExpiry,
                'location'             => $faker->randomElement(['Head Office', 'Branch A', 'Branch B', 'Remote']),
                'status'               => $currentStatus,
                'notes'                => $faker->optional(0.7)->sentence,
            ]);

            // 1. Log Registration (at purchase date)
            $regLog = \App\Models\AssetLog::logEvent($asset->id, 'Registered', null, 'Available', 'Asset purchased and registered.', null, $asset->department_id, $asset);
            $regLog->created_at = $purchaseDate;
            $regLog->save();

            // Simulate history based on current status
            if ($currentStatus == 'Assigned') {
                // If assigned, create an assignment record
                $assignDate = Carbon::parse($purchaseDate)->addDays(rand(5, 30));
                $assigneeId = $faker->randomElement($userIds);
                
                $assignment = \App\Models\AssetAssignment::create([
                    'asset_id' => $asset->id,
                    'user_id' => $assigneeId,
                    'assigned_date' => $assignDate,
                    'expected_return_date' => Carbon::parse($assignDate)->addMonths(rand(3, 12)),
                    'condition_on_assignment' => 'Brand New',
                    'status' => 'Assigned',
                    'assigned_by' => 1,
                ]);

                $log = \App\Models\AssetLog::logEvent($asset->id, 'Assigned', 'Available', 'Assigned', 'Assigned to employee', $assigneeId, $asset->department_id, $assignment);
                $log->created_at = $assignDate;
                $log->save();

            } elseif ($currentStatus == 'Returned' || $currentStatus == 'Maintenance' || $currentStatus == 'Retired' || $currentStatus == 'Disposed') {
                // Simulate an assignment that was later returned
                $assignDate = Carbon::parse($purchaseDate)->addDays(rand(5, 30));
                $assigneeId = $faker->randomElement($userIds);
                
                $assignment = \App\Models\AssetAssignment::create([
                    'asset_id' => $asset->id,
                    'user_id' => $assigneeId,
                    'assigned_date' => $assignDate,
                    'expected_return_date' => Carbon::parse($assignDate)->addMonths(rand(3, 12)),
                    'condition_on_assignment' => 'Good',
                    'status' => 'Assigned',
                    'assigned_by' => 1,
                ]);

                $log = \App\Models\AssetLog::logEvent($asset->id, 'Assigned', 'Available', 'Assigned', 'Assigned to employee', $assigneeId, $asset->department_id, $assignment);
                $log->created_at = $assignDate;
                $log->save();

                // Then returned
                $returnDate = Carbon::parse($assignDate)->addMonths(rand(1, 5));
                $assignment->update([
                    'return_date' => $returnDate,
                    'condition_on_return' => 'Used',
                    'status' => 'Returned',
                    'returned_to' => 1
                ]);

                $log2 = \App\Models\AssetLog::logEvent($asset->id, 'Returned', 'Assigned', 'Available', 'Asset returned', $assigneeId, $asset->department_id, $assignment);
                $log2->created_at = $returnDate;
                $log2->save();
                
                // If status is not just returned (which implies available), add another event
                if ($currentStatus !== 'Returned' && $currentStatus !== 'Available') {
                    $eventDate = Carbon::parse($returnDate)->addDays(rand(1, 10));
                    $eventType = $currentStatus == 'Maintenance' ? 'Maintenance' : 'Note';
                    $log3 = \App\Models\AssetLog::logEvent($asset->id, $eventType, 'Available', $currentStatus, 'Status changed due to condition', null, $asset->department_id, $asset);
                    $log3->created_at = $eventDate;
                    $log3->save();
                }
            }
        }
    }
}
