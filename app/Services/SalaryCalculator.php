<?php

namespace App\Services;

use App\Models\User;
use App\Models\AllowanceSetting;

use App\Models\ProvidentFundSetting;
use App\Models\ProvidentFundContribution;

class SalaryCalculator
{
    public function calculateGrossSalary(User $user, $city = null)
    {
        $basicSalary = $user->basic_salary ?? 0; // Assuming basic_salary is a field on the User model
        $grossSalary = $basicSalary;

        // Get all active global allowance settings
        $globalAllowanceSettings = AllowanceSetting::where('is_active', true)->get();

        foreach ($globalAllowanceSettings as $setting) {
            $allowanceAmount = 0;

            // Check if there's a user-specific override for this allowance
            $userAllowance = $user->userAllowances->where('allowance_setting_id', $setting->id)->first();

            // If user has an override and it's disabled, skip this allowance
            if ($userAllowance && !$userAllowance->is_enabled) {
                continue;
            }

            // Determine the value to use (user-specific or global)
            $valueToUse = $userAllowance && $userAllowance->custom_value !== null
                            ? $userAllowance->custom_value
                            : $setting->value;

            // Calculate allowance based on type
            if ($setting->type === 'percentage') {
                // Handle city-specific HRA
                if ($setting->name === 'HRA' && $setting->city_specific && $city === 'Dhaka') {
                    $allowanceAmount = ($basicSalary * ($setting->city_value / 100));
                } else {
                    $allowanceAmount = ($basicSalary * ($valueToUse / 100));
                }
            } elseif ($setting->type === 'fixed') {
                $allowanceAmount = $valueToUse;
            }

            // Apply tax-free limit for Medical Allowance
            if ($setting->name === 'Medical Allowance' && $setting->tax_free_limit !== null) {
                // Assuming annual tax-free limit, need to adjust for monthly if basic_salary is monthly
                // For simplicity, let's assume basic_salary is monthly and tax_free_limit is annual
                // So, monthly tax-free limit is tax_free_limit / 12
                $monthlyTaxFreeLimit = $setting->tax_free_limit / 12;
                $allowanceAmount = min($allowanceAmount, $monthlyTaxFreeLimit);
            }

            $grossSalary += $allowanceAmount;
        }

        return $grossSalary;
    }

    // You can add a calculateNetSalary method here if deductions are to be handled
    public function calculateNetSalary(User $user, $grossSalary)
    {
        $netSalary = $grossSalary;
        // Apply deductions (tax, provident fund, loans, etc.)
        $netSalary -= $user->welfare_fund_deduction;

        if ($user->is_pf_member) {
            $pfSetting = ProvidentFundSetting::first();
            if ($pfSetting) {
                $employeeContribution = ($user->basic_salary * $pfSetting->employee_contribution) / 100;
                $employerContribution = ($user->basic_salary * $pfSetting->employer_contribution) / 100;

                $netSalary -= $employeeContribution;

                // Create a contribution record
                ProvidentFundContribution::create([
                    'employee_id' => $user->id,
                    'contribution_date' => now(),
                    'employee_contribution' => $employeeContribution,
                    'employer_contribution' => $employerContribution,
                ]);

                // Update user's PF balance
                $user->provident_fund_balance += $employeeContribution + $employerContribution;
                $user->save();
            }
        }

        return $netSalary;
    }
}
