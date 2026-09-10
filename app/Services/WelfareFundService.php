<?php

namespace App\Services;

use App\Models\WelfareFundSetting;
use App\Models\WelfareFundContribution;
use App\Models\MedicalSupport;
use App\Models\FuneralSupport;
use App\Models\EmployeeChildrenEducationSupport;
use App\Models\WelfareSupportAttachment;
use App\Models\WelfareFundAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WelfareFundService
{
    public static function getSettings()
    {
        return WelfareFundSetting::instance();
    }

    public static function calculateEmployeeDeduction(User $user)
    {
        $settings = self::getSettings();
        if (!$settings->welfare_fund_enabled) {
            return 0.00;
        }

        if ($settings->deduction_policy === 'optional' && floatval($user->welfare_fund_deduction) <= 0) {
            return 0.00;
        }

        $baseDeduction = floatval($user->welfare_fund_deduction ?? 0.00);

        if ($settings->deduction_type === 'percentage' && floatval($user->basic_salary ?? 0) > 0) {
            return ($user->basic_salary * $baseDeduction) / 100;
        }

        return $baseDeduction;
    }

    public static function calculateCompanyMatching(User $user, $employeeContribution)
    {
        $settings = self::getSettings();
        if (!$settings->welfare_fund_enabled || !$settings->company_contribution_enabled || $employeeContribution <= 0) {
            return 0.00;
        }

        if ($settings->company_contribution_type === 'matching_percentage') {
            return ($employeeContribution * floatval($settings->company_contribution_amount)) / 100;
        }

        return floatval($settings->company_contribution_amount);
    }

    public static function recordPayrollContributions(User $user, $payrollId = null, $month = null, $year = null)
    {
        $settings = self::getSettings();
        if (!$settings->welfare_fund_enabled) {
            return;
        }

        $month = $month ?? now()->format('F');
        $year = $year ?? now()->format('Y');

        $empDeduction = self::calculateEmployeeDeduction($user);

        DB::transaction(function() use ($user, $payrollId, $month, $year, $empDeduction, $settings) {
            if ($empDeduction > 0) {
                $exists = WelfareFundContribution::where('user_id', $user->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->where('contribution_type', 'employee')
                    ->when($payrollId, function($q) use ($payrollId) {
                        return $q->where('payroll_id', $payrollId);
                    })
                    ->exists();

                if (!$exists) {
                    WelfareFundContribution::create([
                        'user_id' => $user->id,
                        'payroll_id' => $payrollId,
                        'contribution_type' => 'employee',
                        'amount' => $empDeduction,
                        'month' => $month,
                        'year' => $year,
                        'contribution_date' => now(),
                        'remarks' => 'Monthly Payroll Employee Deduction',
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            $compContribution = self::calculateCompanyMatching($user, $empDeduction);
            if ($compContribution > 0) {
                $compExists = WelfareFundContribution::where('user_id', $user->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->where('contribution_type', 'company')
                    ->when($payrollId, function($q) use ($payrollId) {
                        return $q->where('payroll_id', $payrollId);
                    })
                    ->exists();

                if (!$compExists) {
                    WelfareFundContribution::create([
                        'user_id' => $user->id,
                        'payroll_id' => $payrollId,
                        'contribution_type' => 'company',
                        'amount' => $compContribution,
                        'month' => $month,
                        'year' => $year,
                        'contribution_date' => now(),
                        'remarks' => 'Monthly Company Matching Contribution',
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        });
    }

    public static function getFundSummary()
    {
        $employeeContributions = WelfareFundContribution::where('contribution_type', 'employee')->sum('amount');
        $companyContributions = WelfareFundContribution::where('contribution_type', 'company')->sum('amount');
        $adjustmentCredits = WelfareFundContribution::where('contribution_type', 'adjustment')->sum('amount');
        $reversalDebits = WelfareFundContribution::where('contribution_type', 'reversal')->sum('amount');

        $totalCollected = $employeeContributions + $companyContributions + $adjustmentCredits - $reversalDebits;

        $medicalDisbursed = MedicalSupport::where('status', 'Disbursed')->sum('disbursed_amount');
        $funeralDisbursed = FuneralSupport::where('status', 'Disbursed')->sum('disbursed_amount');
        $educationDisbursed = EmployeeChildrenEducationSupport::where('status', 'Disbursed')->sum('disbursed_amount');

        // Fallback to approved_amount / amount if disbursed_amount is null
        if ($medicalDisbursed == 0) {
            $medicalDisbursed = MedicalSupport::where('status', 'Disbursed')->sum('amount');
        }
        if ($funeralDisbursed == 0) {
            $funeralDisbursed = FuneralSupport::where('status', 'Disbursed')->sum('amount');
        }
        if ($educationDisbursed == 0) {
            $educationDisbursed = EmployeeChildrenEducationSupport::where('status', 'Disbursed')->sum('financial_assistance');
        }

        $totalDisbursed = $medicalDisbursed + $funeralDisbursed + $educationDisbursed;

        $currentBalance = $totalCollected - $totalDisbursed;

        $approvedPendingDisbursement = MedicalSupport::where('status', 'Approved')->sum('approved_amount')
            + FuneralSupport::where('status', 'Approved')->sum('approved_amount')
            + EmployeeChildrenEducationSupport::where('status', 'Approved')->sum('approved_amount');

        $availableBalance = $currentBalance - $approvedPendingDisbursement;

        $pendingCount = MedicalSupport::where('status', 'Pending')->count()
            + FuneralSupport::where('status', 'Pending')->count()
            + EmployeeChildrenEducationSupport::where('status', 'Pending')->count();

        $netFundBalance = $currentBalance;
        $totalPending = $pendingCount;

        return compact(
            'employeeContributions',
            'companyContributions',
            'totalCollected',
            'totalDisbursed',
            'currentBalance',
            'netFundBalance',
            'approvedPendingDisbursement',
            'availableBalance',
            'pendingCount',
            'totalPending'
        );
    }

    public static function audit($action, $supportType = null, $supportId = null, $oldStatus = null, $newStatus = null, $remarks = null)
    {
        try {
            WelfareFundAuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'support_type' => $supportType,
                'support_id' => $supportId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'remarks' => $remarks,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Silently swallow audit exceptions to prevent blocking main transaction
        }
    }
}
