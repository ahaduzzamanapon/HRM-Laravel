<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PensionProfile;
use App\Models\PensionEligibilityCheck;
use App\Models\PensionPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EligibilityController extends Controller
{
    public function index(Request $request)
    {
        $query = PensionProfile::with(['user', 'scheme', 'eligibilityCheck', 'departure']);

        $year = $request->input('filter_year');
        $month = $request->input('filter_month');

        if ($year) {
            $query->where(function($q) use ($year) {
                $q->whereHas('departure', function($sub) use ($year) {
                    $sub->whereYear('effective_date', $year);
                })->orWhere(function($sub) use ($year) {
                    $sub->whereDoesntHave('departure')
                        ->whereYear('retirement_date', $year);
                });
            });
        }
        if ($month) {
            $query->where(function($q) use ($month) {
                $q->whereHas('departure', function($sub) use ($month) {
                    $sub->whereMonth('effective_date', $month);
                })->orWhere(function($sub) use ($month) {
                    $sub->whereDoesntHave('departure')
                        ->whereMonth('retirement_date', $month);
                });
            });
        }

        $profiles = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.pension.eligibility.index', compact('profiles'));
    }

    /**
     * Run dynamic automated eligibility checks based on active database rules.
     */
    public function runCheck(Request $request)
    {
        $policy = PensionPolicy::where('status', 'Active')->with('eligibilityRule')->first();
        if (!$policy) {
            return redirect()->back()->with('error', 'No active pension policy found to run checks against.');
        }

        $eligibilityRule = $policy->eligibilityRule;
        if (!$eligibilityRule) {
            return redirect()->back()->with('error', 'No eligibility rules configured for the active pension policy.');
        }

        $scheme = $policy->schemes()->where('status', 'Active')->first() ?? $policy->schemes()->first();
        if (!$scheme) {
            return redirect()->back()->with('error', 'No active schemes found for the policy.');
        }

        $users = \App\Models\User::whereIn('status', ['retired', 'resign', 'left'])
                    ->whereNotNull('date_of_birth')
                    ->whereNotNull('date_of_join')
                    ->with('departures')
                    ->get();
        
        $year = $request->input('year');
        $month = $request->input('month');

        if ($year || $month) {
            $users = $users->filter(function($user) use ($year, $month, $policy) {
                $hasMatchingDeparture = $user->departures->contains(function($dep) use ($year, $month) {
                    $depDate = Carbon::parse($dep->effective_date);
                    $matchYear = !$year || ($depDate->year == $year);
                    $matchMonth = !$month || ($depDate->month == (int)$month);
                    return $matchYear && $matchMonth;
                });

                if ($hasMatchingDeparture) {
                    return true;
                }

                $retirementDate = Carbon::parse($user->date_of_birth)->addYears($policy->retirement_age);
                $matchRetireYear = !$year || ($retirementDate->year == $year);
                $matchRetireMonth = !$month || ($retirementDate->month == (int)$month);
                
                return $matchRetireYear && $matchRetireMonth;
            });
        }

        $count = 0;
        foreach ($users as $user) {
            $birthDate = Carbon::parse($user->date_of_birth);
            $joinDate = Carbon::parse($user->date_of_join);
            
            $age = $birthDate->age;
            
            // Determine retirement date from departure records or default policy retirement age
            $departure = $user->departures->first();
            if ($departure && $departure->effective_date) {
                $retirementDate = Carbon::parse($departure->effective_date);
            } else {
                $retirementDate = $birthDate->copy()->addYears($policy->retirement_age);
            }
            
            // Calculate service years and months
            $diff = $joinDate->diff($retirementDate);
            $serviceYears = max(0, $diff->y);
            $serviceMonths = max(0, $diff->m);
            $totalServiceYears = $serviceYears + ($serviceMonths / 12);

            // 1. Validate Age
            $ageValidated = $age >= $eligibilityRule->min_age && $age <= $eligibilityRule->max_age;
            
            // 2. Validate Service Years
            $minServiceYearsRule = $eligibilityRule->min_service_years ?? $policy->min_service_years ?? 10;
            $maxServiceYearsRule = $eligibilityRule->max_service_years ?? $policy->max_service_years ?? 40;
            $serviceYearsValidated = $totalServiceYears >= $minServiceYearsRule && $totalServiceYears <= $maxServiceYearsRule;

            // 3. Validate Gender if set
            $genderValidated = true;
            if ($eligibilityRule->gender !== 'All' && !empty($user->gender)) {
                $genderValidated = strcasecmp($user->gender, $eligibilityRule->gender) === 0;
            }

            // 4. Validate Department / Designation
            $deptValidated = $eligibilityRule->department_id ? ($user->department_id == $eligibilityRule->department_id) : true;
            $desigValidated = $eligibilityRule->designation_id ? ($user->designation_id == $eligibilityRule->designation_id) : true;

            // 5. Validate Employment Type / Status Rules
            $empTypeValidated = true;
            if ($eligibilityRule->employment_type && $eligibilityRule->employment_type !== 'All') {
                $empTypeValidated = strcasecmp($user->emp_type, $eligibilityRule->employment_type) === 0;
            }
            
            $permanentValidated = true;
            if ($eligibilityRule->is_permanent) {
                $permanentValidated = strcasecmp($user->emp_type, 'Permanent') === 0;
            }

            $statusRuleValidated = true;
            if ($departure) {
                $reason = strtolower($departure->reason ?? '');
                if (str_contains($reason, 'voluntary') && !$policy->voluntary_retirement_allowed) {
                    $statusRuleValidated = false;
                }
                if (str_contains($reason, 'early') && !$policy->early_retirement_allowed) {
                    $statusRuleValidated = false;
                }
                if ($reason === 'resign' && !$policy->voluntary_retirement_allowed) {
                    $statusRuleValidated = false;
                }
            }

            $isEligible = $ageValidated && $serviceYearsValidated && $genderValidated && $deptValidated && $desigValidated && $empTypeValidated && $permanentValidated && $statusRuleValidated;

            // Resolve correct scheme from the policy
            $scheme = null;
            $userGrade = strtolower($user->emp_type ?? '');
            foreach ($policy->schemes as $s) {
                if (str_contains(strtolower($s->name), $userGrade) || str_contains(strtolower($s->type), $userGrade)) {
                    $scheme = $s;
                    break;
                }
            }
            if (!$scheme) {
                $scheme = $policy->schemes()->where('status', 'Active')->first() ?? $policy->schemes()->first();
            }

            // Create or update pension profile
            $profile = PensionProfile::firstOrNew(['user_id' => $user->id]);
            $profile->scheme_id = $profile->scheme_id ?? ($scheme ? $scheme->id : null);
            $profile->retirement_date = $retirementDate->format('Y-m-d');
            $profile->retirement_type = $profile->retirement_type ?? ($departure->reason ?? 'Superannuation');
            $profile->qualifying_service_years = $serviceYears;
            $profile->qualifying_service_months = $serviceMonths;
            $profile->last_basic_pay = ($user->basic_salary > 0) ? $user->basic_salary : (($user->gross_salary > 0) ? $user->gross_salary : ($user->salary ?? 0));
            $profile->eligibility_status = $isEligible ? 'Eligible' : 'Not Eligible';
            $profile->remarks = 'Dynamic Automated Rule Check';
            $profile->save();

            // Record status check logs
            $check = PensionEligibilityCheck::firstOrNew(['profile_id' => $profile->id]);
            $check->age_validated = $ageValidated;
            $check->service_years_validated = $serviceYearsValidated;
            $check->documents_verified = $check->documents_verified ?? false;
            $check->no_disciplinary_cases = $check->no_disciplinary_cases ?? true;
            $check->overall_status = $isEligible ? 'Pass' : 'Pending';
            $check->checked_at = now();
            $check->save();

            $count++;
        }

        $periodText = ($year || $month) ? " for " . ($month ? Carbon::create()->month($month)->format('F') : '') . " " . ($year ?? '') : "";
        return redirect()->back()->with('success', "Processed dynamic eligibility checks{$periodText}. {$count} profiles calculated.");
    }
}
