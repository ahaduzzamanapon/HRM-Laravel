<?php

namespace App\Services;

use App\Models\BonusSetting;
use App\Models\User;
use Carbon\Carbon;

class BonusCalculatorService
{
    /**
     * Calculate and return eligible employees with calculated bonus amounts for a given bonus setting.
     *
     * @param BonusSetting $bonusSetting
     * @param int|null $targetBranchId
     * @return \Illuminate\Support\Collection
     */
    public function calculateForSetting(BonusSetting $bonusSetting, $targetBranchId = null)
    {
        $query = User::with(['branch', 'designation', 'department'])
            ->where('group_id', '!=', 1)
            ->whereNotIn('status', ['retired', 'left', 'resign', 'departed', 'terminated'])
            ->whereDoesntHave('departures');

        // Apply branch filter: targetBranchId > setting branch_id
        $branchId = $targetBranchId ?: $bonusSetting->branch_id;
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        // Apply religion filter if configured
        if (!empty($bonusSetting->religion) && strtolower($bonusSetting->religion) !== 'all') {
            $rel = strtolower($bonusSetting->religion);
            $query->whereRaw('LOWER(religion) = ?', [$rel]);
        }

        $users = $query->get();

        $targetDate = $bonusSetting->bonus_month ? Carbon::parse($bonusSetting->bonus_month) : now();

        return $users->filter(function ($user) use ($bonusSetting, $targetDate) {
            // Check minimum service months requirement
            if ($bonusSetting->min_service_months > 0) {
                $joiningDateStr = $user->date_of_join ?: $user->date_of_joining;
                if (!$joiningDateStr) {
                    return true; // Don't exclude employee if joining date is unassigned unless strict
                }
                $joinDate = Carbon::parse($joiningDateStr);
                $serviceMonths = $joinDate->diffInMonths($targetDate);
                if ($serviceMonths < $bonusSetting->min_service_months) {
                    return false;
                }
            }
            return true;
        })->map(function ($user) use ($bonusSetting) {
            // Determine base amount
            $baseAmount = 0.00;
            if ($bonusSetting->calculation_base === 'basic_salary') {
                $baseAmount = (float)($user->basic_salary ?: ($user->b_salary ?: 0));
            } elseif ($bonusSetting->calculation_base === 'gross_salary') {
                $baseAmount = (float)($user->gross_salary ?: ($user->basic_salary ?: 0));
            }

            // Calculate bonus amount
            $bonusAmount = 0.00;
            if ($bonusSetting->bonus_type === 'percentage') {
                $bonusAmount = round(($baseAmount * (float)$bonusSetting->amount_percentage) / 100, 2);
            } else { // fixed
                $bonusAmount = (float)$bonusSetting->amount_percentage;
            }

            $user->calculated_base_amount = $baseAmount;
            $user->calculated_bonus_amount = $bonusAmount;

            return $user;
        });
    }
}
