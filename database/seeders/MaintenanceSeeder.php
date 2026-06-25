<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\MaintenanceType;
use App\Models\MaintenanceRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Faker\Factory as Faker;

class MaintenanceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Create Maintenance Types
        $types = [
            ['name' => 'Preventive Maintenance', 'description' => 'Regularly scheduled maintenance to prevent failure.', 'status' => 1],
            ['name' => 'Corrective Maintenance', 'description' => 'Repairs made after a fault or failure occurs.', 'status' => 1],
            ['name' => 'Predictive Maintenance', 'description' => 'Condition-based maintenance.', 'status' => 1],
            ['name' => 'Routine Inspection', 'description' => 'General inspection and cleaning.', 'status' => 1],
            ['name' => 'Software Update', 'description' => 'Updating firmware or software applications.', 'status' => 1],
        ];

        foreach ($types as $type) {
            MaintenanceType::updateOrCreate(['name' => $type['name']], $type);
        }

        // 2. Create Vendors
        for ($i = 0; $i < 5; $i++) {
            Vendor::create([
                'name' => $faker->company,
                'contact_person' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->companyEmail,
                'address' => $faker->address,
                'status' => 1,
            ]);
        }

        // 3. Create Maintenance Requests (20 items)
        $categoryIds = AssetCategory::pluck('id')->toArray();
        if (empty($categoryIds)) {
            $cat = AssetCategory::create(['name' => 'IT Equipment']);
            $categoryIds = [$cat->id];
        }

        $assetIds = Asset::pluck('id')->toArray();
        if (empty($assetIds)) {
            for ($i = 1; $i <= 5; $i++) {
                $asset = Asset::create([
                    'category_id' => $faker->randomElement($categoryIds),
                    'asset_code' => 'AST-' . rand(1000, 9999),
                    'name' => 'Dummy Asset ' . $i,
                    'status' => 'Available'
                ]);
                $assetIds[] = $asset->id;
            }
        }

        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin_maint@example.com',
                'password' => bcrypt('password')
            ]);
            $userIds = [$user->id];
        }

        $typeIds = MaintenanceType::pluck('id')->toArray();
        $vendorIds = Vendor::pluck('id')->toArray();

        $statuses = ['Pending', 'Assigned', 'In Progress', 'Completed', 'Cancelled'];
        $priorities = ['Low', 'Medium', 'High', 'Critical'];

        for ($i = 0; $i < 20; $i++) {
            $status = $faker->randomElement($statuses);
            
            $requestedDate = $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d');
            $scheduledDate = $faker->dateTimeBetween($requestedDate, '+1 month')->format('Y-m-d');
            
            $completionDate = null;
            if ($status === 'Completed') {
                $completionDate = $faker->dateTimeBetween($scheduledDate, '+1 month')->format('Y-m-d');
            }

            MaintenanceRequest::create([
                'asset_id' => $faker->randomElement($assetIds),
                'type_id' => $faker->randomElement($typeIds),
                'vendor_id' => $faker->randomElement($vendorIds),
                'requested_by' => $faker->randomElement($userIds),
                'title' => $faker->sentence(4),
                'description' => $faker->paragraph,
                'priority' => $faker->randomElement($priorities),
                'status' => $status,
                'requested_date' => $requestedDate,
                'scheduled_date' => $scheduledDate,
                'completion_date' => $completionDate,
                'cost' => $status === 'Completed' ? $faker->randomFloat(2, 50, 2000) : null,
                'warranty_expiry_date' => $faker->boolean(50) ? $faker->dateTimeBetween('+6 months', '+2 years')->format('Y-m-d') : null,
                'remarks' => $faker->optional()->sentence,
            ]);
        }
    }
}
