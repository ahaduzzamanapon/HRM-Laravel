<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PensionProfile;
use App\Models\PensionCalculation;
use App\Models\PensionDisbursement;
use Carbon\Carbon;

class PensionPhaseTwoSeeder extends Seeder
{
    public function run()
    {
        // Fetch all eligible profiles to create calculations and disbursements
        $profiles = PensionProfile::where('eligibility_status', 'Eligible')->get();

        foreach ($profiles as $profile) {
            // 1. Calculate Pension
            $grossPension = $profile->last_basic_pay * ($profile->scheme->policy->max_pension_percentage ?? 80) / 1;
            $commutedAmount = $grossPension * 0.5 * 12 * 10; // Simple formula for demo
            $monthlyPension = $grossPension * 0.5; // Remaining 50%
            $medicalAllowance = 15;
            $netPension = $monthlyPension + $medicalAllowance;

            $status = ['Draft', 'Approved', 'Finalized'][rand(0, 2)];

            PensionCalculation::create([
                'profile_id' => $profile->id,
                'gross_pension' => $grossPension,
                'commuted_amount' => $commutedAmount,
                'gratuity' => $commutedAmount, // Assuming same for demo
                'medical_allowance' => $medicalAllowance,
                'monthly_pension' => $monthlyPension,
                'net_pension' => $netPension,
                'status' => $status,
            ]);

            // 2. Create Disbursements if Finalized or Approved
            if (in_array($status, ['Approved', 'Finalized'])) {
                $months = [
                    Carbon::w()->subMonths(2)->format('F Y'),
                    Carbon::w()->subMonth()->format('F Y'),
                    Carbon::w()->format('F Y'),
                ];

                foreach ($months as $index => $month) {
                    $paymentStatus = $index === 2 ? 'Pending' : 'Paid';
                    PensionDisbursement::create([
                        'profile_id' => $profile->id,
                        'disbursement_month' => $month,
                        'amount' => $netPension,
                        'arrear_amount' => 0,
                        'deductions' => 0,
                        'net_payable' => $netPension,
                        'payment_method' => ['EFT', 'BEFTN'][rand(0, 1)],
                        'status' => $paymentStatus,
                        'bank_reference' => $paymentStatus === 'Paid' ? 'REF' . rand(10, 999999) : null,
                        'paid_at' => $paymentStatus === 'Paid' ? Carbon::parse($month)->endOfMonth() : null,
                    ]);
                }
            }
        }
    }
}
