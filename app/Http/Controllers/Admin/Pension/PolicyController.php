<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Controller;
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
use App\Models\PensionPolicyVersion;
use App\Models\PensionAuditLog;
use App\Models\PensionProfile;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolicyController extends Controller
{
    /**
     * Display a listing of the policies.
     */
    public function index(Request $request)
    {
        $query = PensionPolicy::with('schemes');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('retirement_age')) {
            $query->where('retirement_age', $request->retirement_age);
        }

        $policies = $query->latest()->paginate(10);
        return view('admin.pension.policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new policy.
     */
    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        return view('admin.pension.policies.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created policy in storage.
     */
    public function store(Request $request)
    {
        $this->validatePolicy($request);

        DB::beginTransaction();
        try {
            // 1. Create Policy (Maker status: Pending Approval)
            $policyData = $request->only([
                'code', 'name', 'description', 'effective_from', 'effective_to',
                'retirement_age', 'min_service_years', 'max_service_years',
                'max_pension_percentage', 'min_pension_amount', 'max_pension_amount',
                'calculation_base'
            ]);
            $policyData['early_retirement_allowed'] = $request->has('early_retirement_allowed');
            $policyData['voluntary_retirement_allowed'] = $request->has('voluntary_retirement_allowed');
            $policyData['status'] = 'Pending Approval';
            $policyData['created_by'] = auth()->id();

            $policy = PensionPolicy::create($policyData);

            // 2. Eligibility Rules
            $eligibilityData = $request->input('eligibility', []);
            $policy->eligibilityRule()->create([
                'min_service_years' => $eligibilityData['min_service_years'] ?? 10,
                'max_service_years' => $eligibilityData['max_service_years'] ?? 40,
                'min_age' => $eligibilityData['min_age'] ?? 40,
                'max_age' => $eligibilityData['max_age'] ?? 80,
                'employment_type' => $eligibilityData['employment_type'] ?? 'Permanent',
                'employee_grade' => $eligibilityData['employee_grade'] ?? 'All',
                'department_id' => $eligibilityData['department_id'] ?? null,
                'designation_id' => $eligibilityData['designation_id'] ?? null,
                'gender' => $eligibilityData['gender'] ?? 'All',
                'is_permanent' => isset($eligibilityData['is_permanent']),
                'is_confirmed' => isset($eligibilityData['is_confirmed']),
                'eligible_for_gratuity' => isset($eligibilityData['eligible_for_gratuity']),
                'eligible_for_family_pension' => isset($eligibilityData['eligible_for_family_pension']),
                'eligible_for_commutation' => isset($eligibilityData['eligible_for_commutation']),
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            // 3. Schemes
            if ($request->has('schemes')) {
                foreach ($request->input('schemes') as $scheme) {
                    $policy->schemes()->create([
                        'code' => $scheme['code'],
                        'name' => $scheme['name'],
                        'type' => $scheme['type'] ?? 'Defined Benefit',
                        'employee_contribution_percentage' => $scheme['employee_contribution_percentage'] ?? 0,
                        'employer_contribution_percentage' => $scheme['employer_contribution_percentage'] ?? 0,
                        'interest_rate' => $scheme['interest_rate'] ?? null,
                        'interest_calculation_frequency' => $scheme['interest_calculation_frequency'] ?? 'N/A',
                        'status' => 'Active',
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // 4. Formula Rules
            if ($request->has('formulas')) {
                foreach ($request->input('formulas') as $formula) {
                    $policy->formulaRules()->create([
                        'name' => $formula['name'],
                        'code' => $formula['code'],
                        'type' => $formula['type'] ?? 'Expression',
                        'expression' => $formula['expression'],
                        'description' => $formula['description'] ?? null,
                        'priority' => $formula['priority'] ?? 1,
                        'status' => 'Active',
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // 5. Commutation Rules & Factors
            $commutationData = $request->input('commutation', []);
            $commutationRule = $policy->commutationRule()->create([
                'enabled' => isset($commutationData['enabled']),
                'max_commutation_percentage' => $commutationData['max_commutation_percentage'] ?? 50.00,
                'default_commutation_percentage' => $commutationData['default_commutation_percentage'] ?? 50.00,
                'is_age_based' => isset($commutationData['is_age_based']),
                'min_age' => $commutationData['min_age'] ?? null,
                'max_age' => $commutationData['max_age'] ?? null,
                'commutation_factor' => $commutationData['commutation_factor'] ?? null,
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            if (isset($commutationData['is_age_based']) && $request->has('factors')) {
                foreach ($request->input('factors') as $factor) {
                    if (isset($factor['age']) && isset($factor['factor'])) {
                        $commutationRule->factors()->create([
                            'age' => $factor['age'],
                            'factor' => $factor['factor'],
                            'status' => 'Active',
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            // 6. Gratuity Rules
            $gratuityData = $request->input('gratuity', []);
            $policy->gratuityRule()->create([
                'enabled' => isset($gratuityData['enabled']),
                'formula_type' => $gratuityData['formula_type'] ?? 'Multiplier Based',
                'formula' => $gratuityData['formula'] ?? null,
                'max_amount' => $gratuityData['max_amount'] ?? 9999999.99,
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            // 7. Medical Rules
            $medicalData = $request->input('medical', []);
            $policy->medicalRule()->create([
                'allowance_type' => $medicalData['allowance_type'] ?? 'Fixed',
                'amount' => $medicalData['amount'] ?? 1500.00,
                'percentage' => $medicalData['percentage'] ?? null,
                'grade' => $medicalData['grade'] ?? null,
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            // 8. Family Rules
            $familyData = $request->input('family', []);
            $policy->familyRule()->create([
                'enabled' => isset($familyData['enabled']),
                'spouse_percentage' => $familyData['spouse_percentage'] ?? 100.00,
                'child_percentage' => $familyData['child_percentage'] ?? 50.00,
                'max_children' => $familyData['max_children'] ?? 2,
                'max_child_age' => $familyData['max_child_age'] ?? 25,
                'disabled_child_lifetime_support' => isset($familyData['disabled_child_lifetime_support']),
                'widow_lifetime_support' => isset($familyData['widow_lifetime_support']),
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            // 9. Revision Rules
            if ($request->has('revisions')) {
                foreach ($request->input('revisions') as $revision) {
                    if (isset($revision['name'])) {
                        $policy->revisionRules()->create([
                            'name' => $revision['name'],
                            'revision_percentage' => $revision['revision_percentage'] ?? 0,
                            'effective_date' => $revision['effective_date'] ?? date('Y-m-d'),
                            'circular_reference' => $revision['circular_reference'] ?? null,
                            'description' => $revision['description'] ?? null,
                            'status' => 'Active',
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            // 10. Tax Rules
            $taxData = $request->input('tax', []);
            $policy->taxRule()->create([
                'taxable' => isset($taxData['taxable']),
                'tax_method' => $taxData['tax_method'] ?? 'Fixed',
                'tax_percentage' => $taxData['tax_percentage'] ?? 0.00,
                'min_exemption' => $taxData['min_exemption'] ?? 0.00,
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            // 11. Payment Rules
            $paymentData = $request->input('payment', []);
            $policy->paymentRule()->create([
                'payment_frequency' => $paymentData['payment_frequency'] ?? 'Monthly',
                'payment_day' => $paymentData['payment_day'] ?? 1,
                'payment_method' => $paymentData['payment_method'] ?? 'EFT',
                'bank_account_required' => isset($paymentData['bank_account_required']),
                'late_payment_interest' => $paymentData['late_payment_interest'] ?? 0.00,
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            // 12. Create Version 1
            $policy->versions()->create([
                'version_number' => 1,
                'effective_date' => $policy->effective_from,
                'change_summary' => 'Initial policy creation and setup of rules.',
                'created_by' => auth()->id(),
            ]);

            // 13. Audit Log
            $this->logAudit('Create', 'PensionPolicy', $policy->id, null, $policy->toArray());

            DB::commit();
            return redirect()->route('admin.pension.policies.index')->with('success', 'Pension Policy created successfully and is pending approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pension policy: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create policy: ' . $e->getMessage());
        }
    }

    /**
     * Show policy details.
     */
    public function show($id)
    {
        $policy = PensionPolicy::with([
            'schemes', 'eligibilityRule', 'formulaRules', 'commutationRule.factors',
            'gratuityRule', 'medicalRule', 'familyRule', 'revisionRules', 'taxRule', 'paymentRule', 'versions.creator'
        ])->findOrFail($id);

        return view('admin.pension.policies.show', compact('policy'));
    }

    /**
     * Show edit policy form.
     */
    public function edit($id)
    {
        $policy = PensionPolicy::with([
            'schemes', 'eligibilityRule', 'formulaRules', 'commutationRule.factors',
            'gratuityRule', 'medicalRule', 'familyRule', 'revisionRules', 'taxRule', 'paymentRule'
        ])->findOrFail($id);

        $departments = Department::all();
        $designations = Designation::all();

        return view('admin.pension.policies.edit', compact('policy', 'departments', 'designations'));
    }

    /**
     * Update the policy (Creates a new version).
     */
    public function update(Request $request, $id)
    {
        $policy = PensionPolicy::findOrFail($id);
        $this->validatePolicy($request, $policy->id);

        DB::beginTransaction();
        try {
            $oldValues = $policy->toArray();

            // 1. Update Policy Details
            $policyData = $request->only([
                'code', 'name', 'description', 'effective_from', 'effective_to',
                'retirement_age', 'min_service_years', 'max_service_years',
                'max_pension_percentage', 'min_pension_amount', 'max_pension_amount',
                'calculation_base'
            ]);
            $policyData['early_retirement_allowed'] = $request->has('early_retirement_allowed');
            $policyData['voluntary_retirement_allowed'] = $request->has('voluntary_retirement_allowed');
            $policyData['status'] = 'Pending Approval'; // Maker status resets to Pending Approval on update
            $policyData['updated_by'] = auth()->id();

            $policy->update($policyData);

            // 2. Update Eligibility Rules
            $eligibilityData = $request->input('eligibility', []);
            $policy->eligibilityRule()->update([
                'min_service_years' => $eligibilityData['min_service_years'] ?? 10,
                'max_service_years' => $eligibilityData['max_service_years'] ?? 40,
                'min_age' => $eligibilityData['min_age'] ?? 40,
                'max_age' => $eligibilityData['max_age'] ?? 80,
                'employment_type' => $eligibilityData['employment_type'] ?? 'Permanent',
                'employee_grade' => $eligibilityData['employee_grade'] ?? 'All',
                'department_id' => $eligibilityData['department_id'] ?? null,
                'designation_id' => $eligibilityData['designation_id'] ?? null,
                'gender' => $eligibilityData['gender'] ?? 'All',
                'is_permanent' => isset($eligibilityData['is_permanent']),
                'is_confirmed' => isset($eligibilityData['is_confirmed']),
                'eligible_for_gratuity' => isset($eligibilityData['eligible_for_gratuity']),
                'eligible_for_family_pension' => isset($eligibilityData['eligible_for_family_pension']),
                'eligible_for_commutation' => isset($eligibilityData['eligible_for_commutation']),
                'updated_by' => auth()->id(),
            ]);

            // 3. Update Schemes (Sync by recreating them for consistency)
            $policy->schemes()->delete();
            if ($request->has('schemes')) {
                foreach ($request->input('schemes') as $scheme) {
                    $policy->schemes()->create([
                        'code' => $scheme['code'],
                        'name' => $scheme['name'],
                        'type' => $scheme['type'] ?? 'Defined Benefit',
                        'employee_contribution_percentage' => $scheme['employee_contribution_percentage'] ?? 0,
                        'employer_contribution_percentage' => $scheme['employer_contribution_percentage'] ?? 0,
                        'interest_rate' => $scheme['interest_rate'] ?? null,
                        'interest_calculation_frequency' => $scheme['interest_calculation_frequency'] ?? 'N/A',
                        'status' => 'Active',
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // 4. Update Formula Rules
            $policy->formulaRules()->delete();
            if ($request->has('formulas')) {
                foreach ($request->input('formulas') as $formula) {
                    $policy->formulaRules()->create([
                        'name' => $formula['name'],
                        'code' => $formula['code'],
                        'type' => $formula['type'] ?? 'Expression',
                        'expression' => $formula['expression'],
                        'description' => $formula['description'] ?? null,
                        'priority' => $formula['priority'] ?? 1,
                        'status' => 'Active',
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // 5. Update Commutation
            $commutationData = $request->input('commutation', []);
            $commutationRule = $policy->commutationRule;
            $commutationRule->update([
                'enabled' => isset($commutationData['enabled']),
                'max_commutation_percentage' => $commutationData['max_commutation_percentage'] ?? 50.00,
                'default_commutation_percentage' => $commutationData['default_commutation_percentage'] ?? 50.00,
                'is_age_based' => isset($commutationData['is_age_based']),
                'min_age' => $commutationData['min_age'] ?? null,
                'max_age' => $commutationData['max_age'] ?? null,
                'commutation_factor' => $commutationData['commutation_factor'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            $commutationRule->factors()->delete();
            if (isset($commutationData['is_age_based']) && $request->has('factors')) {
                foreach ($request->input('factors') as $factor) {
                    if (isset($factor['age']) && isset($factor['factor'])) {
                        $commutationRule->factors()->create([
                            'age' => $factor['age'],
                            'factor' => $factor['factor'],
                            'status' => 'Active',
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            // 6. Gratuity
            $gratuityData = $request->input('gratuity', []);
            $policy->gratuityRule()->update([
                'enabled' => isset($gratuityData['enabled']),
                'formula_type' => $gratuityData['formula_type'] ?? 'Multiplier Based',
                'formula' => $gratuityData['formula'] ?? null,
                'max_amount' => $gratuityData['max_amount'] ?? 9999999.99,
                'updated_by' => auth()->id(),
            ]);

            // 7. Medical
            $medicalData = $request->input('medical', []);
            $policy->medicalRule()->update([
                'allowance_type' => $medicalData['allowance_type'] ?? 'Fixed',
                'amount' => $medicalData['amount'] ?? 1500.00,
                'percentage' => $medicalData['percentage'] ?? null,
                'grade' => $medicalData['grade'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // 8. Family
            $familyData = $request->input('family', []);
            $policy->familyRule()->update([
                'enabled' => isset($familyData['enabled']),
                'spouse_percentage' => $familyData['spouse_percentage'] ?? 100.00,
                'child_percentage' => $familyData['child_percentage'] ?? 50.00,
                'max_children' => $familyData['max_children'] ?? 2,
                'max_child_age' => $familyData['max_child_age'] ?? 25,
                'disabled_child_lifetime_support' => isset($familyData['disabled_child_lifetime_support']),
                'widow_lifetime_support' => isset($familyData['widow_lifetime_support']),
                'updated_by' => auth()->id(),
            ]);

            // 9. Revisions
            $policy->revisionRules()->delete();
            if ($request->has('revisions')) {
                foreach ($request->input('revisions') as $revision) {
                    if (isset($revision['name'])) {
                        $policy->revisionRules()->create([
                            'name' => $revision['name'],
                            'revision_percentage' => $revision['revision_percentage'] ?? 0,
                            'effective_date' => $revision['effective_date'] ?? date('Y-m-d'),
                            'circular_reference' => $revision['circular_reference'] ?? null,
                            'description' => $revision['description'] ?? null,
                            'status' => 'Active',
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            // 10. Tax
            $taxData = $request->input('tax', []);
            $policy->taxRule()->update([
                'taxable' => isset($taxData['taxable']),
                'tax_method' => $taxData['tax_method'] ?? 'Fixed',
                'tax_percentage' => $taxData['tax_percentage'] ?? 0.00,
                'min_exemption' => $taxData['min_exemption'] ?? 0.00,
                'updated_by' => auth()->id(),
            ]);

            // 11. Payment
            $paymentData = $request->input('payment', []);
            $policy->paymentRule()->update([
                'payment_frequency' => $paymentData['payment_frequency'] ?? 'Monthly',
                'payment_day' => $paymentData['payment_day'] ?? 1,
                'payment_method' => $paymentData['payment_method'] ?? 'EFT',
                'bank_account_required' => isset($paymentData['bank_account_required']),
                'late_payment_interest' => $paymentData['late_payment_interest'] ?? 0.00,
                'updated_by' => auth()->id(),
            ]);

            // 12. Create New Version Number
            $latestVersion = $policy->versions()->max('version_number') ?? 1;
            $newVersionNumber = $latestVersion + 1;

            $policy->versions()->create([
                'version_number' => $newVersionNumber,
                'effective_date' => $policy->effective_from,
                'change_summary' => $request->input('change_summary', 'Updated policy rules and structures.'),
                'created_by' => auth()->id(),
            ]);

            // 13. Audit Log
            $this->logAudit('Update', 'PensionPolicy', $policy->id, $oldValues, $policy->toArray());

            DB::commit();
            return redirect()->route('admin.pension.policies.index')->with('success', "Policy updated to Version $newVersionNumber and sent for approval.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pension policy: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update policy: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete policy.
     */
    public function destroy($id)
    {
        $policy = PensionPolicy::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $oldValues = $policy->toArray();
            $policy->delete();
            
            $this->logAudit('Delete', 'PensionPolicy', $id, $oldValues, null);
            DB::commit();
            
            return redirect()->route('admin.pension.policies.index')->with('success', 'Pension Policy soft deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pension.policies.index')->with('error', 'Failed to delete policy: ' . $e->getMessage());
        }
    }

    /**
     * Clone an existing policy.
     */
    public function clonePolicy($id)
    {
        $sourcePolicy = PensionPolicy::with([
            'schemes', 'eligibilityRule', 'formulaRules', 'commutationRule.factors',
            'gratuityRule', 'medicalRule', 'familyRule', 'revisionRules', 'taxRule', 'paymentRule'
        ])->findOrFail($id);

        $departments = Department::all();
        $designations = Designation::all();

        return view('admin.pension.policies.clone', compact('sourcePolicy', 'departments', 'designations'));
    }

    /**
     * Version History Page.
     */
    public function versionHistory($id)
    {
        $policy = PensionPolicy::findOrFail($id);
        $versions = $policy->versions()->with('creator')->latest()->paginate(10);
        return view('admin.pension.policies.versions', compact('policy', 'versions'));
    }

    /**
     * Maker-Checker approvals list.
     */
    public function approvalList()
    {
        $policies = PensionPolicy::where('status', 'Pending Approval')->with('schemes')->latest()->get();
        return view('admin.pension.policies.approvals', compact('policies'));
    }

    /**
     * Approve Policy (Checker action).
     */
    public function approvePolicy(Request $request, $id)
    {
        $policy = PensionPolicy::findOrFail($id);

        DB::beginTransaction();
        try {
            $oldValues = $policy->toArray();

            // Set all other active policies to Inactive
            PensionPolicy::where('status', 'Active')->update([
                'status' => 'Inactive',
                'updated_by' => auth()->id()
            ]);

            // Set current policy status to Active
            $policy->update([
                'status' => 'Active',
                'updated_by' => auth()->id()
            ]);

            $this->logAudit('Approve', 'PensionPolicy', $policy->id, $oldValues, $policy->toArray());
            DB::commit();

            return redirect()->route('admin.pension.policies.approvals')->with('success', 'Pension Policy approved and activated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pension.policies.approvals')->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject Policy (Checker action).
     */
    public function rejectPolicy(Request $request, $id)
    {
        $policy = PensionPolicy::findOrFail($id);

        DB::beginTransaction();
        try {
            $oldValues = $policy->toArray();

            $policy->update([
                'status' => 'Rejected',
                'updated_by' => auth()->id()
            ]);

            $this->logAudit('Reject', 'PensionPolicy', $policy->id, $oldValues, $policy->toArray());
            DB::commit();

            return redirect()->route('admin.pension.policies.approvals')->with('success', 'Pension Policy rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pension.policies.approvals')->with('error', 'Rejection action failed: ' . $e->getMessage());
        }
    }

    /**
     * View Audit Logs.
     */
    public function auditLogs()
    {
        $logs = PensionAuditLog::with('user')->latest()->paginate(15);
        return view('admin.pension.policies.audit_logs', compact('logs'));
    }

    /**
     * Policy Scheme Assignment page.
     */
    public function assignmentIndex()
    {
        $employees = User::where('status', 'Active')->orWhere('status', 'retired')->get();
        $schemes = PensionScheme::where('status', 'Active')->with('policy')->get();
        $assignments = PensionProfile::with(['user', 'scheme.policy'])->latest()->paginate(15);

        return view('admin.pension.policies.assignments', compact('employees', 'schemes', 'assignments'));
    }

    /**
     * Store scheme assignment.
     */
    public function assignmentStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'scheme_id' => 'required|exists:pension_schemes,id',
            'last_basic_pay' => 'required|numeric|min:0',
            'qualifying_service_years' => 'required|integer|min:0',
            'qualifying_service_months' => 'required|integer|min:0|max:11',
            'retirement_date' => 'nullable|date',
            'retirement_type' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $profile = PensionProfile::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'scheme_id' => $request->scheme_id,
                    'last_basic_pay' => $request->last_basic_pay,
                    'qualifying_service_years' => $request->qualifying_service_years,
                    'qualifying_service_months' => $request->qualifying_service_months,
                    'retirement_date' => $request->retirement_date,
                    'retirement_type' => $request->retirement_type ?? 'Superannuation',
                    'eligibility_status' => 'Eligible',
                ]
            );

            $this->logAudit('AssignScheme', 'PensionProfile', $profile->id, null, $profile->toArray());
            DB::commit();

            return redirect()->route('admin.pension.policies.assignments')->with('success', 'Pension Scheme assigned to employee successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Assignment failed: ' . $e->getMessage());
        }
    }

    /**
     * Audit logger helper.
     */
    private function logAudit($action, $modelType, $modelId, $oldValues = null, $newValues = null)
    {
        try {
            PensionAuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::error('Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate Policy requests.
     */
    private function validatePolicy(Request $request, $id = null)
    {
        $codeRule = 'required|string|max:50|unique:pension_policies,code';
        if ($id) {
            $codeRule .= ',' . $id;
        }

        $request->validate([
            'code' => $codeRule,
            'name' => 'required|string|max:255',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'retirement_age' => 'required|integer|min:40|max:80',
            'min_service_years' => 'required|integer|min:0|max:50',
            'max_service_years' => 'required|integer|min:0|max:60|gte:min_service_years',
            'max_pension_percentage' => 'required|numeric|min:0|max:100',
            'min_pension_amount' => 'required|numeric|min:0',
            'max_pension_amount' => 'required|numeric|gte:min_pension_amount',
            'calculation_base' => 'required|string|in:Last Basic Salary,Last Gross Salary,Average Last 12 Months,Average Last 24 Months,Average Last 36 Months',
            
            // Schemes
            'schemes' => 'required|array|min:1',
            'schemes.*.code' => 'required|string|distinct',
            'schemes.*.name' => 'required|string',
            'schemes.*.employee_contribution_percentage' => 'required|numeric|min:0|max:100',
            'schemes.*.employer_contribution_percentage' => 'required|numeric|min:0|max:100',
            
            // Formulas
            'formulas' => 'required|array|min:1',
            'formulas.*.code' => 'required|string|distinct',
            'formulas.*.name' => 'required|string',
            'formulas.*.expression' => 'required|string',
        ]);
    }
}
