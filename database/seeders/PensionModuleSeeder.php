<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PensionPolicy;
use App\Models\PensionScheme;
use App\Models\PensionProfile;
use App\Models\PensionEligibilityCheck;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PensionModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create 3 Dummy Pension Policies
        $policies = [
            [
                'name' => 'Govt. Standard Pension 2026',
                'description' => 'Standard government employee pension policy with full benefits.',
                'retirement_age' => 59,
                'min_qualifying_service_years' => 10,
                'max_pension_percentage' => 90.00,
                'is_active' => true,
                'schemes' => [
                    ['name' => 'General Provident Fund (GPF)', 'contribution_percentage' => 10.00],
                    ['name' => 'Benevolent Fund', 'contribution_percentage' => 2.00],
                ]
            ],
            [
                'name' => 'Bank Staff Contributory Pension',
                'description' => 'Contributory pension scheme for bank staff.',
                'retirement_age' => 60,
                'min_qualifying_service_years' => 15,
                'max_pension_percentage' => 80.00,
                'is_active' => true,
                'schemes' => [
                    ['name' => 'Contributory Provident Fund (CPF)', 'contribution_percentage' => 12.00],
                ]
            ],
            [
                'name' => 'Early Voluntary Retirement (EVR)',
                'description' => 'Special policy for early voluntary retirement seekers.',
                'retirement_age' => 50,
                'min_qualifying_service_years' => 20,
                'max_pension_percentage' => 75.00,
                'is_active' => true,
                'schemes' => [
                    ['name' => 'Voluntary Scheme A', 'contribution_percentage' => 5.00],
                ]
            ],
        ];

        $createdPolicies = [];
        foreach ($policies as $policyData) {
            $policy = PensionPolicy::create([
                'name' => $policyData['name'],
                'description' => $policyData['description'],
                'retirement_age' => $policyData['retirement_age'],
                'min_qualifying_service_years' => $policyData['min_qualifying_service_years'],
                'max_pension_percentage' => $policyData['max_pension_percentage'],
                'is_active' => $policyData['is_active'],
            ]);

            foreach ($policyData['schemes'] as $schemeData) {
                $policy->schemes()->create([
                    'name' => $schemeData['name'],
                    'contribution_percentage' => $schemeData['contribution_percentage'],
                    'is_active' => true,
                ]);
            }
            $createdPolicies[] = $policy;
        }

        // Fetch all created schemes to assign to profiles randomly
        $schemes = PensionScheme::all();
        if ($schemes->isEmpty()) {
            return; // Safety check
        }

        // 2. Fetch up to 15 existing users, or create them if they don't exist
        $users = User::limit(15)->get();
        if ($users->count() < 10) {
            // Create dummy users to reach 10
            for ($i = 0; $i < (10 - $users->count()); $i++) {
                $user = User::create([
                    'name' => 'Pension User ' . ($i + 1),
                    'email' => 'pension' . ($i + 1) . '@example.com',
                    'date_of_birth' => Carbon::now()->subYears(rand(55, 62))->format('Y-m-d'),
                    'date_of_join' => Carbon::now()->subYears(rand(10, 35))->format('Y-m-d'),
                    'salary' => rand(30000, 150000),
                ]);
            }
            $users = User::limit(15)->get();
        }

        // 3. Create Pension Profiles and Eligibility Checks for each user
        $statuses = ['Eligible', 'Not Eligible', 'Pending Review'];
        $overallStatuses = ['Pass', 'Fail', 'Pending'];

        foreach ($users as $index => $user) {
            if ($index >= 12) break; // Create exactly 12 records
            
            $scheme = $schemes->random();
            $yearsOfService = rand(8, 35);
            $retirementType = $yearsOfService > 25 ? 'Superannuation' : 'Voluntary';
            $status = $statuses[array_rand($statuses)];
            $overallStatus = $status === 'Eligible' ? 'Pass' : ($status === 'Not Eligible' ? 'Fail' : 'Pending');
            
            $profile = PensionProfile::create([
                'user_id' => $user->id,
                'scheme_id' => $scheme->id,
                'retirement_date' => Carbon::parse($user->date_of_birth ?? Carbon::now()->subYears(60))->addYears(60)->format('Y-m-d'),
                'retirement_type' => $retirementType,
                'qualifying_service_years' => $yearsOfService,
                'qualifying_service_months' => rand(0, 11),
                'last_basic_pay' => $user->salary ?? rand(25000, 100000),
                'eligibility_status' => $status,
                'remarks' => 'Automated dummy data generation.',
            ]);

            PensionEligibilityCheck::create([
                'profile_id' => $profile->id,
                'age_validated' => rand(0, 1) == 1,
                'service_years_validated' => $yearsOfService >= 10,
                'documents_verified' => rand(0, 1) == 1,
                'no_disciplinary_cases' => rand(0, 10) > 2, // mostly true
                'lwp_impact_details' => rand(0, 1) == 1 ? '3 months LWP deducted' : null,
                'overall_status' => $overallStatus,
                'checked_by' => null,
                'checked_at' => $overallStatus !== 'Pending' ? Carbon::now() : null,
            ]);
        }
    }
}
