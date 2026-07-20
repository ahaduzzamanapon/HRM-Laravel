<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PensionCalculation;
use App\Models\PensionProfile;
use App\Models\PensionPolicy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CalculationController extends Controller
{
    public function index(Request $request)
    {
        $query = PensionCalculation::with(['profile.user', 'profile.departure']);

        $year = $request->input('filter_year');
        $month = $request->input('filter_month');

        if ($year) {
            $query->whereHas('profile', function($q) use ($year) {
                $q->where(function($subQ) use ($year) {
                    $subQ->whereHas('departure', function($sub) use ($year) {
                        $sub->whereYear('effective_date', $year);
                    })->orWhere(function($sub) use ($year) {
                        $sub->whereDoesntHave('departure')
                            ->whereYear('retirement_date', $year);
                    });
                });
            });
        }
        if ($month) {
            $query->whereHas('profile', function($q) use ($month) {
                $q->where(function($subQ) use ($month) {
                    $subQ->whereHas('departure', function($sub) use ($month) {
                        $sub->whereMonth('effective_date', $month);
                    })->orWhere(function($sub) use ($month) {
                        $sub->whereDoesntHave('departure')
                            ->whereMonth('retirement_date', $month);
                    });
                });
            });
        }

        $calculations = $query->latest()->paginate(10)->appends($request->all());
        return view('admin.pension.calculations.index', compact('calculations'));
    }

    /**
     * Process calculations dynamically using formula expressions stored in the database.
     */
    public function process()
    {
        // Delete draft calculations to prevent duplication and force recalculation
        PensionCalculation::where('status', 'Draft')->delete();

        $eligibleProfiles = PensionProfile::where('eligibility_status', 'Eligible')
            ->whereDoesntHave('calculation')
            ->get();

        $count = 0;
        foreach ($eligibleProfiles as $profile) {
            $scheme = $profile->scheme;
            $policy = $scheme->policy ?? null;
            if (!$policy || $policy->status !== 'Active') {
                // If scheme has no active policy, search for the globally active policy
                $policy = PensionPolicy::where('status', 'Active')->first();
            }
            
            if (!$policy) {
                Log::warning("No active pension policy found for profile ID: {$profile->id}");
                continue;
            }

            // Determine base salary based on policy calculation base
            $baseSalary = $profile->last_basic_pay ?? 0.00;
            if ($policy->calculation_base === 'Last Gross Salary' && $profile->user && $profile->user->gross_salary > 0) {
                $baseSalary = $profile->user->gross_salary;
            } elseif ($policy->calculation_base === 'Last Basic Salary' && $profile->user && $profile->user->basic_salary > 0) {
                $baseSalary = $profile->user->basic_salary;
            }
            if ($baseSalary <= 0) {
                $baseSalary = $profile->last_basic_pay ?? 0.00;
            }

            // Calculate pension percentage
            $schemeRate = $scheme->employer_contribution_percentage ?? 10.00;
            $serviceFraction = $profile->qualifying_service_years + (($profile->qualifying_service_months ?? 0) / 12);
            $calculatedPercentage = $serviceFraction * $schemeRate;
            $pensionPercentage = min($policy->max_pension_percentage, $calculatedPercentage);

            // Determine age at retirement for commutation factor lookup
            $birthDate = $profile->user ? \Carbon\Carbon::parse($profile->user->date_of_birth) : now();
            $retireDate = \Carbon\Carbon::parse($profile->retirement_date ?? now());
            $ageAtRetirement = $birthDate->diffInYears($retireDate);

            $commutationPercentage = 50.00;
            $commutationFactor = 10.00;
            
            if ($policy->commutationRule && $policy->commutationRule->enabled) {
                $commRule = $policy->commutationRule;
                $commutationPercentage = $commRule->default_commutation_percentage ?? $commRule->max_commutation_percentage ?? 50.00;
                
                if ($commRule->is_age_based) {
                    $factorRecord = \App\Models\PensionCommutationFactor::where('commutation_rule_id', $commRule->id)
                        ->where('age', round($ageAtRetirement))
                        ->first();
                    if ($factorRecord) {
                        $commutationFactor = $factorRecord->factor;
                    } else {
                        $commutationFactor = $commRule->commutation_factor ?? 10.00;
                    }
                } else {
                    $commutationFactor = $commRule->commutation_factor ?? 10.00;
                }
            }

            // Determine Medical Allowance from Policy Medical Rule
            $medicalAllowanceAmount = 1500.00;
            if ($policy->medicalRule && $policy->medicalRule->status === 'Active') {
                $medRule = $policy->medicalRule;
                if ($medRule->allowance_type === 'Fixed') {
                    $medicalAllowanceAmount = $medRule->amount;
                } elseif ($medRule->allowance_type === 'Percentage' && $medRule->percentage > 0) {
                    $medicalAllowanceAmount = $baseSalary * ($medRule->percentage / 100);
                }
            }

            // Determine Tax Deduction from Policy Tax Rule
            $taxAmount = 0.00;
            if ($policy->taxRule && $policy->taxRule->taxable) {
                $taxRule = $policy->taxRule;
                if ($taxRule->tax_method === 'Percentage' && $taxRule->tax_percentage > 0) {
                    $taxAmount = $baseSalary * ($taxRule->tax_percentage / 100);
                } elseif ($taxRule->tax_method === 'Fixed') {
                    $taxAmount = $taxRule->tax_percentage;
                }
            }

            // Calculate revisions/annual increments
            $revisionPercentage = 0.00;
            $activeRevisions = $policy->revisionRules()
                ->where('status', 'Active')
                ->where('effective_date', '<=', now()->format('Y-m-d'))
                ->get();
            foreach ($activeRevisions as $revision) {
                $revisionPercentage += $revision->revision_percentage;
            }

            // Populate variables for the formula evaluation
            $variables = [
                'basic' => $baseSalary,
                'service_years' => $serviceFraction,
                'percentage' => $pensionPercentage,
                'commutation_factor' => $commutationFactor,
                'commutation_percentage' => $commutationPercentage,
                'commutation_fraction' => $commutationPercentage / 100,
                'medical_allowance' => $medicalAllowanceAmount,
                'tax_amount' => $taxAmount,
                'revision_percentage' => $revisionPercentage,
                'revision_factor' => 1 + ($revisionPercentage / 100),
            ];

            // Load and evaluate formulas in Priority order
            $formulas = $policy->formulaRules()->where('status', 'Active')->orderBy('priority')->get();

            foreach ($formulas as $formula) {
                $expression = $formula->expression;
                if ($formula->code === 'COMMUTED_AMOUNT') {
                    $expression = str_replace(['0.5', '10'], ['[commutation_fraction]', '[commutation_factor]'], $expression);
                }
                if ($formula->code === 'MONTHLY_PENSION') {
                    $expression = str_replace('0.5', '(1 - [commutation_fraction])', $expression);
                }

                $evaluatedValue = $this->evaluateExpression($expression, $variables);
                
                if ($formula->code === 'MEDICAL_ALLOWANCE' && $policy->medicalRule && $policy->medicalRule->status === 'Active') {
                    $evaluatedValue = $medicalAllowanceAmount;
                }
                
                $variables[strtolower($formula->code)] = $evaluatedValue;
            }

            // Parse values safely with defaults
            $grossPension = $variables['gross_pension'] ?? 0.00;
            $commutedAmount = $variables['commuted_amount'] ?? 0.00;
            
            // Gratuity calculation from gratuityRule
            $gratuity = 0.00;
            if ($policy->gratuityRule && $policy->gratuityRule->enabled) {
                $gratRule = $policy->gratuityRule;
                $gratuityFormula = $gratRule->formula ?? '[basic] * [service_years] * 1.5';
                $gratuity = $this->evaluateExpression($gratuityFormula, $variables);
                if ($gratRule->max_amount > 0 && $gratuity > $gratRule->max_amount) {
                    $gratuity = $gratRule->max_amount;
                }
            } else {
                $gratuity = $variables['gratuity'] ?? $commutedAmount;
            }

            $medicalAllowance = $variables['medical_allowance'] ?? $medicalAllowanceAmount;
            $monthlyPension = $variables['monthly_pension'] ?? ($grossPension * (1 - ($commutationPercentage / 100)));
            
            // Apply revision/annual increment
            $monthlyPension = $monthlyPension * (1 + ($revisionPercentage / 100));

            // Net monthly pension
            $netPension = $variables['net_payable'] ?? ($monthlyPension + $medicalAllowance - $taxAmount);

            // Apply policy caps if defined
            if ($policy->min_pension_amount > 0 && $netPension < $policy->min_pension_amount) {
                $netPension = $policy->min_pension_amount;
            }
            if ($policy->max_pension_amount > 0 && $netPension > $policy->max_pension_amount) {
                $netPension = $policy->max_pension_amount;
            }

            PensionCalculation::create([
                'profile_id' => $profile->id,
                'gross_pension' => $grossPension,
                'commuted_amount' => $commutedAmount,
                'gratuity' => $gratuity,
                'medical_allowance' => $medicalAllowance,
                'monthly_pension' => $monthlyPension,
                'net_pension' => $netPension,
                'status' => 'Draft',
            ]);
            
            $count++;
        }

        return redirect()->back()->with('success', "Processed pension calculations for {$count} profiles based on active policy formulas.");
    }

    /**
     * Helper to safely parse and evaluate database formula expressions.
     */
    private function evaluateExpression($expression, $variables)
    {
        // Replace variable tokens
        foreach ($variables as $key => $val) {
            $expression = str_replace('[' . $key . ']', floatval($val), $expression);
        }

        // Sanitize string to allow only mathematical characters
        $sanitized = preg_replace('/[^0-9\+\-\*\/\(\)\. ]/', '', $expression);

        if (trim($sanitized) === '') {
            return 0.00;
        }

        try {
            // Evaluate safely using PHP's eval
            $result = eval('return ' . $sanitized . ';');
            return floatval($result);
        } catch (\Throwable $e) {
            Log::error("Failed evaluating expression: '{$sanitized}' due to: " . $e->getMessage());
            return 0.00;
        }
    }
}
