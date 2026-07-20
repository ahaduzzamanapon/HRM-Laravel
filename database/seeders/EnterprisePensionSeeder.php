<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PensionPolicy;
use App\Models\PensionScheme;
use App\Models\PensionEligibilityRule;
use App\Models\PensionFormulaRule;
use App\Models\PensionCommutationRule;
use App\Models\PensionCommutationFactor;
use App\Models\PensionGratuityRule;
use App\Models\PensionMedicalRule;
use App\Models\PensionFamilyRule;
use App\Models\PensionRevisionRule;
use App\Models\PensionTaxRule;
use App\Models\PensionPaymentRule;
use Illuminate\Support\Facades\DB;

class EnterprisePensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Create standard Policy
        $policy = PensionPolicy::create([
            'code' => 'POL-2026-001',
            'name' => 'Commercial Bank Standard Pension Policy',
            'description' => 'Standard pension policy for banking employees updated for fiscal year 2026.',
            'effective_from' => '2026-01-01',
            'effective_to' => null,
            'retirement_age' => 60,
            'min_service_years' => 10,
            'max_service_years' => 40,
            'early_retirement_allowed' => true,
            'voluntary_retirement_allowed' => true,
            'calculation_base' => 'Last Basic Salary',
            'max_pension_percentage' => 100.00,
            'min_pension_amount' => 2000.00,
            'max_pension_amount' => 50000.00,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Schemes
        $generalScheme = PensionScheme::create([
            'policy_id' => $policy->id,
            'code' => 'SCH-GEN-001',
            'name' => 'General Staff Pension Scheme',
            'type' => 'General Employee',
            'employee_contribution_percentage' => 5.00,
            'employer_contribution_percentage' => 10.00,
            'interest_rate' => 7.50,
            'interest_calculation_frequency' => 'Monthly',
            'status' => 'Active',
            'created_by' => 1,
        ]);

        $officerScheme = PensionScheme::create([
            'policy_id' => $policy->id,
            'code' => 'SCH-OFF-001',
            'name' => 'Management & Officers Scheme',
            'type' => 'Officer',
            'employee_contribution_percentage' => 8.00,
            'employer_contribution_percentage' => 12.00,
            'interest_rate' => 8.00,
            'interest_calculation_frequency' => 'Quarterly',
            'status' => 'Active',
            'created_by' => 1,
        ]);

        $executiveScheme = PensionScheme::create([
            'policy_id' => $policy->id,
            'code' => 'SCH-EXE-001',
            'name' => 'Executive & Management Scheme',
            'type' => 'Executive',
            'employee_contribution_percentage' => 10.00,
            'employer_contribution_percentage' => 15.00,
            'interest_rate' => 8.50,
            'interest_calculation_frequency' => 'Semi-Annually',
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Eligibility Rules
        PensionEligibilityRule::create([
            'policy_id' => $policy->id,
            'min_service_years' => 10,
            'max_service_years' => 45,
            'min_age' => 40,
            'max_age' => 80,
            'employment_type' => 'Permanent',
            'employee_grade' => 'All',
            'department_id' => null,
            'designation_id' => null,
            'gender' => 'All',
            'is_permanent' => true,
            'is_confirmed' => true,
            'eligible_for_gratuity' => true,
            'eligible_for_family_pension' => true,
            'eligible_for_commutation' => true,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Calculation Formulas
        $formulas = [
            [
                'name' => 'Gross Pension',
                'code' => 'GROSS_PENSION',
                'type' => 'Expression',
                'expression' => '[basic] * ([service_years] * 10 / 100)',
                'description' => 'Gross pension based on last basic pay and qualifying service years multiplier.',
                'priority' => 1,
            ],
            [
                'name' => 'Commuted Amount (Lump Sum)',
                'code' => 'COMMUTED_AMOUNT',
                'type' => 'Expression',
                'expression' => '[gross_pension] * 0.5 * 12 * 10',
                'description' => 'One-time lump sum commutation payout representing 50% of the pension.',
                'priority' => 2,
            ],
            [
                'name' => 'Monthly Pension',
                'code' => 'MONTHLY_PENSION',
                'type' => 'Expression',
                'expression' => '[gross_pension] * 0.5',
                'description' => 'Monthly running pension equivalent to the remaining 50% of gross pension.',
                'priority' => 3,
            ],
            [
                'name' => 'Medical Allowance',
                'code' => 'MEDICAL_ALLOWANCE',
                'type' => 'Fixed',
                'expression' => '1500',
                'description' => 'Fixed monthly medical allowance added to running pension.',
                'priority' => 4,
            ],
            [
                'name' => 'Net Monthly Payable',
                'code' => 'NET_PAYABLE',
                'type' => 'Expression',
                'expression' => '[monthly_pension] + [medical_allowance]',
                'description' => 'The actual monthly pension amount transferred to the bank account.',
                'priority' => 5,
            ]
        ];

        foreach ($formulas as $f) {
            PensionFormulaRule::create(array_merge($f, [
                'policy_id' => $policy->id,
                'status' => 'Active',
                'created_by' => 1,
            ]));
        }

        // Create Commutation Rules
        $commutationRule = PensionCommutationRule::create([
            'policy_id' => $policy->id,
            'enabled' => true,
            'max_commutation_percentage' => 50.00,
            'default_commutation_percentage' => 50.00,
            'is_age_based' => true,
            'min_age' => 50,
            'max_age' => 65,
            'commutation_factor' => null,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Age-wise Commutation Factors
        $factors = [
            50 => 12.15,
            51 => 11.98,
            52 => 11.81,
            53 => 11.64,
            54 => 11.47,
            55 => 11.30,
            56 => 11.10,
            57 => 10.90,
            58 => 10.70,
            59 => 10.50,
            60 => 10.00,
            61 => 9.50,
            62 => 9.00,
            63 => 8.75,
            64 => 8.50,
            65 => 8.25,
        ];

        foreach ($factors as $age => $factor) {
            PensionCommutationFactor::create([
                'commutation_rule_id' => $commutationRule->id,
                'age' => $age,
                'factor' => $factor,
                'status' => 'Active',
                'created_by' => 1,
            ]);
        }

        // Create Gratuity Rules
        PensionGratuityRule::create([
            'policy_id' => $policy->id,
            'enabled' => true,
            'formula_type' => 'Multiplier Based',
            'formula' => '[basic] * [service_years] * 1.5',
            'max_amount' => 5000000.00,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Medical Rules
        PensionMedicalRule::create([
            'policy_id' => $policy->id,
            'allowance_type' => 'Fixed',
            'amount' => 1500.00,
            'percentage' => null,
            'grade' => null,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Family Rules
        PensionFamilyRule::create([
            'policy_id' => $policy->id,
            'enabled' => true,
            'spouse_percentage' => 100.00,
            'child_percentage' => 50.00,
            'max_children' => 2,
            'max_child_age' => 25,
            'disabled_child_lifetime_support' => true,
            'widow_lifetime_support' => true,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Revision Rules
        PensionRevisionRule::create([
            'policy_id' => $policy->id,
            'name' => 'FY 2026 Annual Pension Increment',
            'revision_percentage' => 5.00,
            'effective_date' => '2026-07-01',
            'circular_reference' => 'BB/PSD/2026-102',
            'description' => '5% annual increment for all retired employees to match living cost adjustments.',
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Tax Rules
        PensionTaxRule::create([
            'policy_id' => $policy->id,
            'taxable' => false,
            'tax_method' => 'Fixed',
            'tax_percentage' => 0.00,
            'min_exemption' => 300000.00,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Create Payment Rules
        PensionPaymentRule::create([
            'policy_id' => $policy->id,
            'payment_frequency' => 'Monthly',
            'payment_day' => 1,
            'payment_method' => 'EFT',
            'bank_account_required' => true,
            'late_payment_interest' => 1.50,
            'status' => 'Active',
            'created_by' => 1,
        ]);

        // Seed some sample profiles if schemes exist to fix foreign keys
        // We will match with existing users to assign standard schemes
        $users = DB::table('users')->limit(5)->pluck('id');
        foreach ($users as $userId) {
            // Check if profile already exists or delete it
            DB::table('pension_profiles')->where('user_id', $userId)->delete();
            
            DB::table('pension_profiles')->insert([
                'user_id' => $userId,
                'scheme_id' => $officerScheme->id,
                'retirement_date' => '2025-12-15',
                'retirement_type' => 'Superannuation',
                'qualifying_service_years' => 25,
                'qualifying_service_months' => 6,
                'last_basic_pay' => 45000.00,
                'eligibility_status' => 'Eligible',
                'remarks' => 'Seeded automatically via Enterprise Pension System.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
