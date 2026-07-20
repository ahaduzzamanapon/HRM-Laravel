<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Delete draft calculations
\App\Models\PensionCalculation::where('status', 'Draft')->delete();

$eligibleProfiles = \App\Models\PensionProfile::whereDoesntHave('calculation')
    ->get();
    
$count = 0;
foreach ($eligibleProfiles as $profile) {
    $scheme = $profile->scheme;
    $policy = $scheme->policy ?? null;
    if (!$policy) {
        $policy = \App\Models\PensionPolicy::where('is_active', true)->first();
    }
    if (!$policy) continue;

    $schemeRate = $scheme->contribution_percentage ?? 10;
    $calculatedPercentage = $profile->qualifying_service_years * $schemeRate;
    $pensionPercentage = min($policy->max_pension_percentage, $calculatedPercentage);

    $grossPension = $profile->last_basic_pay * ($pensionPercentage / 100);
    $commutedAmount = $grossPension * 0.5 * 12 * 10;
    $monthlyPension = $grossPension * 0.5;
    $medicalAllowance = 1500;
    $netPension = $monthlyPension + $medicalAllowance;

    \App\Models\PensionCalculation::create([
        'profile_id' => $profile->id,
        'gross_pension' => $grossPension,
        'commuted_amount' => $commutedAmount,
        'gratuity' => $commutedAmount,
        'medical_allowance' => $medicalAllowance,
        'monthly_pension' => $monthlyPension,
        'net_pension' => $netPension,
        'status' => 'Draft',
    ]);
    $count++;
}

print "RECALCULATED PENSIONS COUNT: " . $count . "\n";
print_r(\App\Models\PensionCalculation::take(2)->get()->toArray());
