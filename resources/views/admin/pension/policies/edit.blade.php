@extends('layouts.default')

@section('title')
Edit Pension Policy @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fa fa-edit mr-2 text-primary"></i>Edit Pension Policy (V{{ $policy->versions()->max('version_number') }})</h1>
                <p class="text-muted">Updating this policy will create a new version and submit it for Checker approval.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.pension.policies.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    #policyTabs {
        background-color: #0177bc;
        padding: 0 !important;
        border-bottom: none;
    }
    #policyTabs .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        border: none;
        border-radius: 0;
        transition: all 0.2s ease-in-out;
    }
    #policyTabs .nav-link:hover {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.15) !important;
    }
    #policyTabs .nav-link.active {
        color: #0177bc !important;
        background-color: #fff !important;
        border-bottom: 3px solid #0056b3;
    }
</style>

<div class="content px-3">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i> Please correct the highlighted errors.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pension.policies.update', $policy->id) }}" id="policyForm">
        @csrf
        @method('PUT')
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-header p-0 border-bottom">
                <ul class="nav nav-tabs nav-justified" id="policyTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-3" id="general-tab" data-toggle="tab" href="#general" role="tab"><i class="fa fa-info-circle mr-1"></i> General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="eligibility-tab" data-toggle="tab" href="#eligibility" role="tab"><i class="fa fa-user-check mr-1"></i> Eligibility</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="schemes-tab" data-toggle="tab" href="#schemes" role="tab"><i class="fa fa-th-list mr-1"></i> Schemes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="formulas-tab" data-toggle="tab" href="#formulas" role="tab"><i class="fa fa-calculator mr-1"></i> Formulas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="commutation-tab" data-toggle="tab" href="#commutation" role="tab"><i class="fa fa-chart-pie mr-1"></i> Commutation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="gratuity-tab" data-toggle="tab" href="#gratuity" role="tab"><i class="fa fa-gift mr-1"></i> Gratuity & Med</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="family-tab" data-toggle="tab" href="#family" role="tab"><i class="fa fa-users mr-1"></i> Family & Rev</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3" id="misc-tab" data-toggle="tab" href="#misc" role="tab"><i class="fa fa-cog mr-1"></i> Tax & Payment</a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content" id="policyTabsContent">
                    
                    <!-- TAB 1: GENERAL INFO -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">General Information</h5>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Policy Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $policy->code) }}" required placeholder="e.g. POL-2026-001">
                                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold">Policy Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $policy->name) }}" required placeholder="e.g. FY 2026 Commercial Bank General Pension Policy">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Provide background context or legislative policy reference...">{{ old('description', $policy->description) }}</textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Effective From <span class="text-danger">*</span></label>
                                <input type="date" name="effective_from" class="form-control @error('effective_from') is-invalid @enderror" value="{{ old('effective_from', $policy->effective_from) }}" required>
                                @error('effective_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Effective To</label>
                                <input type="date" name="effective_to" class="form-control @error('effective_to') is-invalid @enderror" value="{{ old('effective_to', $policy->effective_to) }}">
                                @error('effective_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Retirement Age <span class="text-danger">*</span></label>
                                <input type="number" name="retirement_age" class="form-control @error('retirement_age') is-invalid @enderror" value="{{ old('retirement_age', $policy->retirement_age) }}" required>
                                @error('retirement_age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Min Service Years <span class="text-danger">*</span></label>
                                <input type="number" name="min_service_years" class="form-control @error('min_service_years') is-invalid @enderror" value="{{ old('min_service_years', $policy->min_service_years) }}" required>
                                @error('min_service_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Max Service Years <span class="text-danger">*</span></label>
                                <input type="number" name="max_service_years" class="form-control @error('max_service_years') is-invalid @enderror" value="{{ old('max_service_years', $policy->max_service_years) }}" required>
                                @error('max_service_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Calculation Base <span class="text-danger">*</span></label>
                                <select name="calculation_base" class="form-control" required>
                                    <option value="Last Basic Salary" {{ old('calculation_base', $policy->calculation_base) === 'Last Basic Salary' ? 'selected' : '' }}>Last Basic Salary</option>
                                    <option value="Last Gross Salary" {{ old('calculation_base', $policy->calculation_base) === 'Last Gross Salary' ? 'selected' : '' }}>Last Gross Salary</option>
                                    <option value="Average Last 12 Months" {{ old('calculation_base', $policy->calculation_base) === 'Average Last 12 Months' ? 'selected' : '' }}>Average Last 12 Months</option>
                                    <option value="Average Last 24 Months" {{ old('calculation_base', $policy->calculation_base) === 'Average Last 24 Months' ? 'selected' : '' }}>Average Last 24 Months</option>
                                    <option value="Average Last 36 Months" {{ old('calculation_base', $policy->calculation_base) === 'Average Last 36 Months' ? 'selected' : '' }}>Average Last 36 Months</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Max Pension Percentage (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="max_pension_percentage" class="form-control @error('max_pension_percentage') is-invalid @enderror" value="{{ old('max_pension_percentage', $policy->max_pension_percentage) }}" required>
                                @error('max_pension_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Min Pension Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="min_pension_amount" class="form-control @error('min_pension_amount') is-invalid @enderror" value="{{ old('min_pension_amount', $policy->min_pension_amount) }}" required>
                                @error('min_pension_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Max Pension Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="max_pension_amount" class="form-control @error('max_pension_amount') is-invalid @enderror" value="{{ old('max_pension_amount', $policy->max_pension_amount) }}" required>
                                @error('max_pension_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-6 mt-4">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" id="early_retirement_allowed" name="early_retirement_allowed" class="custom-control-input" value="1" {{ old('early_retirement_allowed', $policy->early_retirement_allowed) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="early_retirement_allowed">Allow Early Retirement</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline ml-4">
                                    <input type="checkbox" id="voluntary_retirement_allowed" name="voluntary_retirement_allowed" class="custom-control-input" value="1" {{ old('voluntary_retirement_allowed', $policy->voluntary_retirement_allowed) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="voluntary_retirement_allowed">Allow Voluntary Retirement</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: ELIGIBILITY RULES -->
                    <div class="tab-pane fade" id="eligibility" role="tabpanel">
                        <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">Eligibility Rules</h5>
                        @php $el = $policy->eligibilityRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Min Age for Pension</label>
                                <input type="number" name="eligibility[min_age]" class="form-control" value="{{ $el->min_age ?? 40 }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Max Age for Pension</label>
                                <input type="number" name="eligibility[max_age]" class="form-control" value="{{ $el->max_age ?? 80 }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Min Qualifying Service Years</label>
                                <input type="number" name="eligibility[min_service_years]" class="form-control" value="{{ $el->min_service_years ?? 10 }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold">Max Qualifying Service Years</label>
                                <input type="number" name="eligibility[max_service_years]" class="form-control" value="{{ $el->max_service_years ?? 45 }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Employment Type</label>
                                <select name="eligibility[employment_type]" class="form-control">
                                    <option value="Permanent" {{ ($el->employment_type ?? '') === 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option value="Contract" {{ ($el->employment_type ?? '') === 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="All" {{ ($el->employment_type ?? '') === 'All' ? 'selected' : '' }}>All Types</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Employee Grade</label>
                                <input type="text" name="eligibility[employee_grade]" class="form-control" value="{{ $el->employee_grade ?? 'All' }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Gender Eligibility</label>
                                <select name="eligibility[gender]" class="form-control">
                                    <option value="All" {{ ($el->gender ?? '') === 'All' ? 'selected' : '' }}>All Genders</option>
                                    <option value="Male" {{ ($el->gender ?? '') === 'Male' ? 'selected' : '' }}>Male Only</option>
                                    <option value="Female" {{ ($el->gender ?? '') === 'Female' ? 'selected' : '' }}>Female Only</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Target Department</label>
                                <select name="eligibility[department_id]" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ ($el->department_id ?? null) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Target Designation</label>
                                <select name="eligibility[designation_id]" class="form-control">
                                    <option value="">All Designations</option>
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig->id }}" {{ ($el->designation_id ?? null) == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12 mt-3">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" id="is_permanent" name="eligibility[is_permanent]" class="custom-control-input" value="1" {{ ($el->is_permanent ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="is_permanent">Must Be Permanent</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline ml-4">
                                    <input type="checkbox" id="is_confirmed" name="eligibility[is_confirmed]" class="custom-control-input" value="1" {{ ($el->is_confirmed ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="is_confirmed">Must Be Confirmed</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline ml-4">
                                    <input type="checkbox" id="eligible_for_gratuity" name="eligibility[eligible_for_gratuity]" class="custom-control-input" value="1" {{ ($el->eligible_for_gratuity ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="eligible_for_gratuity">Eligible For Gratuity</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline ml-4">
                                    <input type="checkbox" id="eligible_for_family_pension" name="eligibility[eligible_for_family_pension]" class="custom-control-input" value="1" {{ ($el->eligible_for_family_pension ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="eligible_for_family_pension">Eligible For Family Pension</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline ml-4">
                                    <input type="checkbox" id="eligible_for_commutation" name="eligibility[eligible_for_commutation]" class="custom-control-input" value="1" {{ ($el->eligible_for_commutation ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="eligible_for_commutation">Eligible For Commutation</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: SCHEMES -->
                    <div class="tab-pane fade" id="schemes" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                            <h5 class="text-primary font-weight-bold mb-0">Policy Schemes</h5>
                            <button type="button" class="btn btn-sm btn-primary shadow-sm" id="addSchemeBtn">
                                <i class="fa fa-plus-circle mr-1"></i> Add Scheme Row
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="schemesTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Scheme Code <span class="text-danger">*</span></th>
                                        <th>Scheme Name <span class="text-danger">*</span></th>
                                        <th>Scheme Type</th>
                                        <th>Employee Contribution %</th>
                                        <th>Employer Contribution %</th>
                                        <th>Interest Rate %</th>
                                        <th>Interest Calc Freq</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policy->schemes as $idx => $scheme)
                                    <tr>
                                        <td><input type="text" name="schemes[{{ $idx }}][code]" class="form-control" value="{{ $scheme->code }}" required></td>
                                        <td><input type="text" name="schemes[{{ $idx }}][name]" class="form-control" value="{{ $scheme->name }}" required></td>
                                        <td>
                                            <select name="schemes[{{ $idx }}][type]" class="form-control">
                                                <option value="General Employee" {{ $scheme->type === 'General Employee' ? 'selected' : '' }}>General Employee</option>
                                                <option value="Officer" {{ $scheme->type === 'Officer' ? 'selected' : '' }}>Officer</option>
                                                <option value="Executive" {{ $scheme->type === 'Executive' ? 'selected' : '' }}>Executive</option>
                                                <option value="Government" {{ $scheme->type === 'Government' ? 'selected' : '' }}>Government</option>
                                                <option value="Defined Benefit" {{ $scheme->type === 'Defined Benefit' ? 'selected' : '' }}>Defined Benefit</option>
                                                <option value="Defined Contribution" {{ $scheme->type === 'Defined Contribution' ? 'selected' : '' }}>Defined Contribution</option>
                                                <option value="Hybrid" {{ $scheme->type === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                                <option value="Provident Fund" {{ $scheme->type === 'Provident Fund' ? 'selected' : '' }}>Provident Fund</option>
                                            </select>
                                        </td>
                                        <td><input type="number" step="0.01" name="schemes[{{ $idx }}][employee_contribution_percentage]" class="form-control" value="{{ $scheme->employee_contribution_percentage }}" required></td>
                                        <td><input type="number" step="0.01" name="schemes[{{ $idx }}][employer_contribution_percentage]" class="form-control" value="{{ $scheme->employer_contribution_percentage }}" required></td>
                                        <td><input type="number" step="0.01" name="schemes[{{ $idx }}][interest_rate]" class="form-control" value="{{ $scheme->interest_rate }}"></td>
                                        <td>
                                            <select name="schemes[{{ $idx }}][interest_calculation_frequency]" class="form-control">
                                                <option value="Monthly" {{ $scheme->interest_calculation_frequency === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="Quarterly" {{ $scheme->interest_calculation_frequency === 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                                <option value="Semi-Annually" {{ $scheme->interest_calculation_frequency === 'Semi-Annually' ? 'selected' : '' }}>Semi-Annually</option>
                                                <option value="Annually" {{ $scheme->interest_calculation_frequency === 'Annually' ? 'selected' : '' }}>Annually</option>
                                                <option value="N/A" {{ $scheme->interest_calculation_frequency === 'N/A' ? 'selected' : '' }}>N/A</option>
                                            </select>
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-row-btn" {{ $policy->schemes->count() == 1 ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 4: CALCULATION FORMULAS -->
                    <div class="tab-pane fade" id="formulas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                            <h5 class="text-primary font-weight-bold mb-0">Calculation Formulas</h5>
                            <button type="button" class="btn btn-sm btn-primary shadow-sm" id="addFormulaBtn">
                                <i class="fa fa-plus-circle mr-1"></i> Add Formula Row
                            </button>
                        </div>
                        <div class="alert alert-light border text-secondary mb-4">
                            <strong><i class="fa fa-info-circle mr-1"></i> Formula Guide:</strong> Use mathematical expressions. Place tokens in brackets. Supported tokens: 
                            <span class="badge badge-secondary">[basic]</span> (Last Basic Salary), 
                            <span class="badge badge-secondary">[service_years]</span> (Qualifying Service Years), 
                            <span class="badge badge-secondary">[percentage]</span> (Pension Percentage calculated dynamically),
                            and lowercased codes of previous formulas (e.g. <span class="badge badge-secondary">[gross_pension]</span>, <span class="badge badge-secondary">[monthly_pension]</span>, etc.).
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="formulasTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 150px;">Formula Code <span class="text-danger">*</span></th>
                                        <th style="width: 200px;">Formula Name <span class="text-danger">*</span></th>
                                        <th style="width: 150px;">Type</th>
                                        <th>Formula Expression <span class="text-danger">*</span></th>
                                        <th style="width: 250px;">Description</th>
                                        <th style="width: 100px;">Priority</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policy->formulaRules as $idx => $formula)
                                    <tr>
                                        <td><input type="text" name="formulas[{{ $idx }}][code]" class="form-control" value="{{ $formula->code }}" required></td>
                                        <td><input type="text" name="formulas[{{ $idx }}][name]" class="form-control" value="{{ $formula->name }}" required></td>
                                        <td>
                                            <select name="formulas[{{ $idx }}][type]" class="form-control">
                                                <option value="Expression" {{ $formula->type === 'Expression' ? 'selected' : '' }}>Expression</option>
                                                <option value="Fixed" {{ $formula->type === 'Fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                <option value="Percentage" {{ $formula->type === 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="formulas[{{ $idx }}][expression]" class="form-control font-weight-bold" value="{{ $formula->expression }}" required></td>
                                        <td><input type="text" name="formulas[{{ $idx }}][description]" class="form-control" value="{{ $formula->description }}"></td>
                                        <td><input type="number" name="formulas[{{ $idx }}][priority]" class="form-control text-center" value="{{ $formula->priority }}" required></td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-row-btn" {{ $policy->formulaRules->count() == 1 ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 5: COMMUTATION -->
                    <div class="tab-pane fade" id="commutation" role="tabpanel">
                        <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">Commutation Rules</h5>
                        @php $com = $policy->commutationRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="commutation_enabled" name="commutation[enabled]" value="1" {{ ($com->enabled ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="commutation_enabled">Enable Pension Commutation (Lump Sum Gratuity)</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Max Commutation Percentage (%)</label>
                                <input type="number" step="0.01" name="commutation[max_commutation_percentage]" class="form-control" value="{{ $com->max_commutation_percentage ?? 50.00 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Default Commutation Percentage (%)</label>
                                <input type="number" step="0.01" name="commutation[default_commutation_percentage]" class="form-control" value="{{ $com->default_commutation_percentage ?? 50.00 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Commutation Mode</label>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" id="is_age_based" name="commutation[is_age_based]" value="1" {{ ($com->is_age_based ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="is_age_based">Age Based Commutation Factors</label>
                                </div>
                            </div>
                        </div>

                        <!-- Age Based factors configuration -->
                        <div id="ageBasedFactorsSection" class="mt-4" style="{{ ($com->is_age_based ?? false) ? '' : 'display: none;' }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-secondary font-weight-bold mb-0"><i class="fa fa-table mr-1"></i>Age-wise Commutation Factors</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addFactorBtn">
                                    <i class="fa fa-plus-circle mr-1"></i> Add Factor Row
                                </button>
                            </div>
                            <div style="max-width: 600px;">
                                <table class="table table-sm table-bordered" id="factorsTable">
                                    <thead class="bg-light">
                                        <tr class="text-center">
                                            <th>Age (Years)</th>
                                            <th>Commutation Factor</th>
                                            <th style="width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($com->factors ?? [] as $fidx => $factor)
                                        <tr>
                                            <td><input type="number" name="factors[{{ $fidx }}][age]" class="form-control form-control-sm text-center" value="{{ $factor->age }}" required></td>
                                            <td><input type="number" step="0.01" name="factors[{{ $fidx }}][factor]" class="form-control form-control-sm text-center" value="{{ $factor->factor }}" required></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm delete-row-btn"><i class="fa fa-trash text-sm"></i></button></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td><input type="number" name="factors[0][age]" class="form-control form-control-sm text-center" value="60" required></td>
                                            <td><input type="number" step="0.01" name="factors[0][factor]" class="form-control form-control-sm text-center" value="10.00" required></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm delete-row-btn" disabled><i class="fa fa-trash text-sm"></i></button></td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: GRATUITY & MEDICAL -->
                    <div class="tab-pane fade" id="gratuity" role="tabpanel">
                        <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">Gratuity Rules</h5>
                        @php $grat = $policy->gratuityRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="gratuity_enabled" name="gratuity[enabled]" value="1" {{ ($grat->enabled ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="gratuity_enabled">Enable Gratuity Payout</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Gratuity Formula Type</label>
                                <select name="gratuity[formula_type]" class="form-control">
                                    <option value="Multiplier Based" {{ ($grat->formula_type ?? '') === 'Multiplier Based' ? 'selected' : '' }}>Multiplier Based</option>
                                    <option value="Salary Based" {{ ($grat->formula_type ?? '') === 'Salary Based' ? 'selected' : '' }}>Salary Based</option>
                                    <option value="Service Based" {{ ($grat->formula_type ?? '') === 'Service Based' ? 'selected' : '' }}>Service Based</option>
                                    <option value="Fixed Amount" {{ ($grat->formula_type ?? '') === 'Fixed Amount' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Custom Formula Expression</label>
                                <input type="text" name="gratuity[formula]" class="form-control" value="{{ $grat->formula ?? '' }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Maximum Cap Limit (৳)</label>
                                <input type="number" step="0.01" name="gratuity[max_amount]" class="form-control" value="{{ $grat->max_amount ?? 5000000.00 }}">
                            </div>
                        </div>

                        <h5 class="text-primary font-weight-bold mt-5 mb-4 border-bottom pb-2">Medical Allowance Rules</h5>
                        @php $med = $policy->medicalRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Medical Allowance Type</label>
                                <select name="medical[allowance_type]" class="form-control">
                                    <option value="Fixed" {{ ($med->allowance_type ?? '') === 'Fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="Percentage" {{ ($med->allowance_type ?? '') === 'Percentage' ? 'selected' : '' }}>Percentage of Pension</option>
                                    <option value="Grade Based" {{ ($med->allowance_type ?? '') === 'Grade Based' ? 'selected' : '' }}>Grade Based</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Allowance Amount (৳)</label>
                                <input type="number" step="0.01" name="medical[amount]" class="form-control" value="{{ $med->amount ?? 1500.00 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Percentage (%) (If percentage type)</label>
                                <input type="number" step="0.01" name="medical[percentage]" class="form-control" value="{{ $med->percentage ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 7: FAMILY & REVISION -->
                    <div class="tab-pane fade" id="family" role="tabpanel">
                        <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">Family Pension Support</h5>
                        @php $fam = $policy->familyRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="family_enabled" name="family[enabled]" value="1" {{ ($fam->enabled ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="family_enabled">Enable Family Pension (Transfers to spouse/children upon death)</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Spouse Pension Transfer Percentage (%)</label>
                                <input type="number" step="0.01" name="family[spouse_percentage]" class="form-control" value="{{ $fam->spouse_percentage ?? 100.00 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Child Pension Transfer Percentage (%)</label>
                                <input type="number" step="0.01" name="family[child_percentage]" class="form-control" value="{{ $fam->child_percentage ?? 50.00 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Max Children Allowed Support</label>
                                <input type="number" name="family[max_children]" class="form-control" value="{{ $fam->max_children ?? 2 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Max Child Age Support (Years)</label>
                                <input type="number" name="family[max_child_age]" class="form-control" value="{{ $fam->max_child_age ?? 25 }}">
                            </div>
                            <div class="form-group col-md-4 mt-4 pt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="disabled_child_lifetime" name="family[disabled_child_lifetime_support]" value="1" {{ ($fam->disabled_child_lifetime_support ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="disabled_child_lifetime">Lifetime Disabled Child Support</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mt-4 pt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="widow_lifetime" name="family[widow_lifetime_support]" value="1" {{ ($fam->widow_lifetime_support ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="widow_lifetime">Lifetime Widow Support</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 mb-4 border-bottom pb-2">
                            <h5 class="text-primary font-weight-bold mb-0">Policy Revisions / Annual Increments</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addRevisionBtn">
                                <i class="fa fa-plus-circle mr-1"></i> Add Revision Row
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="revisionsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Revision Circular / Name</th>
                                        <th>Increment Percentage (%)</th>
                                        <th>Effective Date</th>
                                        <th>Circular Code</th>
                                        <th>Description</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policy->revisionRules as $ridx => $revision)
                                    <tr>
                                        <td><input type="text" name="revisions[{{ $ridx }}][name]" class="form-control" value="{{ $revision->name }}" required></td>
                                        <td><input type="number" step="0.01" name="revisions[{{ $ridx }}][revision_percentage]" class="form-control text-center" value="{{ $revision->revision_percentage }}"></td>
                                        <td><input type="date" name="revisions[{{ $ridx }}][effective_date]" class="form-control" value="{{ $revision->effective_date }}"></td>
                                        <td><input type="text" name="revisions[{{ $ridx }}][circular_reference]" class="form-control" value="{{ $revision->circular_reference }}"></td>
                                        <td><input type="text" name="revisions[{{ $ridx }}][description]" class="form-control" value="{{ $revision->description }}"></td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-row-btn"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 8: TAX & PAYMENT RULES -->
                    <div class="tab-pane fade" id="misc" role="tabpanel">
                        <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">Tax Settings</h5>
                        @php $tax = $policy->taxRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="tax_taxable" name="tax[taxable]" value="1" {{ ($tax->taxable ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="tax_taxable">Pension is Subject to Tax Deductions</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Tax Valuation Method</label>
                                <select name="tax[tax_method]" class="form-control">
                                    <option value="Fixed" {{ ($tax->tax_method ?? '') === 'Fixed' ? 'selected' : '' }}>Fixed Percentage</option>
                                    <option value="Tax Slab" {{ ($tax->tax_method ?? '') === 'Tax Slab' ? 'selected' : '' }}>NBR Standard Slab</option>
                                    <option value="Exempted" {{ ($tax->tax_method ?? '') === 'Exempted' ? 'selected' : '' }}>Exempted</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Tax Deduction Rate (%)</label>
                                <input type="number" step="0.01" name="tax[tax_percentage]" class="form-control" value="{{ $tax->tax_percentage ?? 0.00 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Tax Minimum Exemption Amount (৳)</label>
                                <input type="number" step="0.01" name="tax[min_exemption]" class="form-control" value="{{ $tax->min_exemption ?? 300000.00 }}">
                            </div>
                        </div>

                        <h5 class="text-primary font-weight-bold mt-5 mb-4 border-bottom pb-2">Disbursement Payment Settings</h5>
                        @php $pay = $policy->paymentRule; @endphp
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Payment Frequency</label>
                                <select name="payment[payment_frequency]" class="form-control">
                                    <option value="Monthly" {{ ($pay->payment_frequency ?? '') === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="Quarterly" {{ ($pay->payment_frequency ?? '') === 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="Yearly" {{ ($pay->payment_frequency ?? '') === 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Disbursement Calendar Day</label>
                                <input type="number" name="payment[payment_day]" class="form-control" value="{{ $pay->payment_day ?? 1 }}" min="1" max="28">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Transfer Channel</label>
                                <select name="payment[payment_method]" class="form-control">
                                    <option value="EFT" {{ ($pay->payment_method ?? '') === 'EFT' ? 'selected' : '' }}>EFT (Electronic Funds Transfer)</option>
                                    <option value="BEFTN" {{ ($pay->payment_method ?? '') === 'BEFTN' ? 'selected' : '' }}>BEFTN (Bangladesh Electronic Funds Transfer)</option>
                                    <option value="RTGS" {{ ($pay->payment_method ?? '') === 'RTGS' ? 'selected' : '' }}>RTGS (Real Time Gross Settlement)</option>
                                    <option value="Bank Transfer" {{ ($pay->payment_method ?? '') === 'Bank Transfer' ? 'selected' : '' }}>Direct Bank Transfer</option>
                                    <option value="Cash" {{ ($pay->payment_method ?? '') === 'Cash' ? 'selected' : '' }}>Cash / Cheque</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 mt-2">
                                <div class="custom-control custom-checkbox mt-4">
                                    <input type="checkbox" class="custom-control-input" id="bank_account_required" name="payment[bank_account_required]" value="1" {{ ($pay->bank_account_required ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="bank_account_required">Validate Active Routing Account No.</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Late Payment Penalty Interest Rate (%)</label>
                                <input type="number" step="0.01" name="payment[late_payment_interest]" class="form-control" value="{{ $pay->late_payment_interest ?? 0.00 }}">
                            </div>
                        </div>

                        <h5 class="text-warning font-weight-bold mt-5 mb-3"><i class="fa fa-info-circle mr-1"></i> Version Control</h5>
                        <div class="row bg-light p-3 border rounded">
                            <div class="form-group col-md-12 mb-0">
                                <label class="font-weight-bold">Change Summary (Version Note) <span class="text-danger">*</span></label>
                                <input type="text" name="change_summary" class="form-control" required placeholder="Describe what has changed in this version..." value="{{ old('change_summary') }}">
                                <small class="text-muted">Example: Updated the NBR standard tax exemption slabs and modified child pension support age constraints.</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="card-footer bg-light text-right p-4">
                <button type="button" class="btn btn-secondary mr-2" id="prevTabBtn" style="display: none;"><i class="fa fa-chevron-left mr-1"></i> Previous</button>
                <button type="button" class="btn btn-info mr-2" id="nextTabBtn">Next <i class="fa fa-chevron-right ml-1"></i></button>
                <button type="submit" class="btn btn-success shadow-sm" id="submitBtn" style="display: none;"><i class="fa fa-save mr-1"></i> Save Changes & Send for Approval</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var tabs = ['#general', '#eligibility', '#schemes', '#formulas', '#commutation', '#gratuity', '#family', '#misc'];
        var currentTabIdx = 0;

        function updateTabButtons() {
            if (currentTabIdx === 0) {
                $('#prevTabBtn').hide();
            } else {
                $('#prevTabBtn').show();
            }

            if (currentTabIdx === tabs.length - 1) {
                $('#nextTabBtn').hide();
                $('#submitBtn').show();
            } else {
                $('#nextTabBtn').show();
                $('#submitBtn').hide();
            }
        }

        $('#nextTabBtn').click(function() {
            if (currentTabIdx < tabs.length - 1) {
                currentTabIdx++;
                $('#policyTabs li:eq(' + currentTabIdx + ') a').tab('show');
                updateTabButtons();
            }
        });

        $('#prevTabBtn').click(function() {
            if (currentTabIdx > 0) {
                currentTabIdx--;
                $('#policyTabs li:eq(' + currentTabIdx + ') a').tab('show');
                updateTabButtons();
            }
        });

        $('#policyTabs a').on('shown.bs.tab', function (e) {
            var targetId = $(e.target).attr('href');
            currentTabIdx = tabs.indexOf(targetId);
            updateTabButtons();
        });

        $('#is_age_based').change(function() {
            if (this.checked) {
                $('#ageBasedFactorsSection').slideDown();
            } else {
                $('#ageBasedFactorsSection').slideUp();
            }
        });

        // Dynamic Scheme Rows
        var schemeIdx = {{ $policy->schemes->count() }};
        $('#addSchemeBtn').click(function() {
            var row = `<tr>
                <td><input type="text" name="schemes[${schemeIdx}][code]" class="form-control" required placeholder="e.g. SCH-002"></td>
                <td><input type="text" name="schemes[${schemeIdx}][name]" class="form-control" required placeholder="e.g. Senior Executive"></td>
                <td>
                    <select name="schemes[${schemeIdx}][type]" class="form-control">
                        <option value="General Employee">General Employee</option>
                        <option value="Officer">Officer</option>
                        <option value="Executive">Executive</option>
                        <option value="Government">Government</option>
                        <option value="Defined Benefit">Defined Benefit</option>
                        <option value="Defined Contribution">Defined Contribution</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Provident Fund">Provident Fund</option>
                    </select>
                </td>
                <td><input type="number" step="0.01" name="schemes[${schemeIdx}][employee_contribution_percentage]" class="form-control" value="5.00" required></td>
                <td><input type="number" step="0.01" name="schemes[${schemeIdx}][employer_contribution_percentage]" class="form-control" value="10.00" required></td>
                <td><input type="number" step="0.01" name="schemes[${schemeIdx}][interest_rate]" class="form-control" value="7.50"></td>
                <td>
                    <select name="schemes[${schemeIdx}][interest_calculation_frequency]" class="form-control">
                        <option value="Monthly" selected>Monthly</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Semi-Annually">Semi-Annually</option>
                        <option value="Annually">Annually</option>
                        <option value="N/A">N/A</option>
                    </select>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm delete-row-btn"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
            $('#schemesTable tbody').append(row);
            schemeIdx++;
        });

        // Dynamic Formulas
        var formulaIdx = {{ $policy->formulaRules->count() }};
        $('#addFormulaBtn').click(function() {
            var row = `<tr>
                <td><input type="text" name="formulas[${formulaIdx}][code]" class="form-control" required placeholder="e.g. BONUS_RULE"></td>
                <td><input type="text" name="formulas[${formulaIdx}][name]" class="form-control" required placeholder="e.g. Special Allowance"></td>
                <td>
                    <select name="formulas[${formulaIdx}][type]" class="form-control">
                        <option value="Expression" selected>Expression</option>
                        <option value="Fixed">Fixed Amount</option>
                        <option value="Percentage">Percentage</option>
                    </select>
                </td>
                <td><input type="text" name="formulas[${formulaIdx}][expression]" class="form-control font-weight-bold" required placeholder="[basic] * 0.05"></td>
                <td><input type="text" name="formulas[${formulaIdx}][description]" class="form-control" placeholder="Allowance details..."></td>
                <td><input type="number" name="formulas[${formulaIdx}][priority]" class="form-control text-center" value="${formulaIdx + 1}" required></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm delete-row-btn"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
            $('#formulasTable tbody').append(row);
            formulaIdx++;
        });

        // Dynamic Factors
        var factorIdx = {{ $policy->commutationRule->factors->count() ?? 0 }};
        $('#addFactorBtn').click(function() {
            var row = `<tr>
                <td><input type="number" name="factors[${factorIdx}][age]" class="form-control form-control-sm text-center" required placeholder="61"></td>
                <td><input type="number" step="0.01" name="factors[${factorIdx}][factor]" class="form-control form-control-sm text-center" required placeholder="9.50"></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm delete-row-btn"><i class="fa fa-trash text-sm"></i></button></td>
            </tr>`;
            $('#factorsTable tbody').append(row);
            factorIdx++;
        });

        // Dynamic Revisions
        var revisionIdx = {{ $policy->revisionRules->count() }};
        $('#addRevisionBtn').click(function() {
            var row = `<tr>
                <td><input type="text" name="revisions[${revisionIdx}][name]" class="form-control" required placeholder="e.g. Annual revision 2027"></td>
                <td><input type="number" step="0.01" name="revisions[${revisionIdx}][revision_percentage]" class="form-control text-center" value="5.00"></td>
                <td><input type="date" name="revisions[${revisionIdx}][effective_date]" class="form-control" value="{{ date('Y-m-d') }}"></td>
                <td><input type="text" name="revisions[${revisionIdx}][circular_reference]" class="form-control" placeholder="Ref code"></td>
                <td><input type="text" name="revisions[${revisionIdx}][description]" class="form-control" placeholder="Details..."></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm delete-row-btn"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
            $('#revisionsTable tbody').append(row);
            revisionIdx++;
        });

        $(document).on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush
@endsection
