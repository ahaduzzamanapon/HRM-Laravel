@extends('layouts.default')

@section('title')
Pension Policy Details @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h3 class="font-weight-bold">Policy Details: {{ $policy->code }}</h3>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.pension.policies.edit', $policy->id) }}" class="btn btn-primary mr-2 shadow-sm">
                    <i class="fa fa-edit mr-1"></i> Edit Policy
                </a>
                <a href="{{ route('admin.pension.policies.clone', $policy->id) }}" class="btn btn-success mr-2 shadow-sm">
                    <i class="fa fa-clone mr-1"></i> Clone Policy
                </a>
                <a href="{{ route('admin.pension.policies.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <!-- Policy General Info Summary Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="font-weight-bold mb-0">{{ $policy->name }}</h4>
                        @if($policy->status === 'Active')
                            <span class="badge badge-success px-3 py-2 rounded-pill font-weight-bold"><i class="fa fa-check-circle mr-1"></i>Active</span>
                        @elseif($policy->status === 'Pending Approval')
                            <span class="badge badge-warning px-3 py-2 rounded-pill font-weight-bold text-white"><i class="fa fa-clock mr-1"></i>Pending Checker Approval</span>
                        @elseif($policy->status === 'Rejected')
                            <span class="badge badge-danger px-3 py-2 rounded-pill font-weight-bold"><i class="fa fa-times-circle mr-1"></i>Rejected</span>
                        @else
                            <span class="badge badge-secondary px-3 py-2 rounded-pill font-weight-bold">Inactive</span>
                        @endif
                    </div>
                    <p class="text-secondary">{{ $policy->description ?? 'No policy description provided.' }}</p>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <span class="text-muted d-block small">Effective Period</span>
                            <strong>
                                {{ \Carbon\Carbon::parse($policy->effective_from)->format('d M Y') }} - 
                                {{ $policy->effective_to ? \Carbon\Carbon::parse($policy->effective_to)->format('d M Y') : 'Ongoing' }}
                            </strong>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <span class="text-muted d-block small">Retirement Age</span>
                            <strong>{{ $policy->retirement_age }} Years</strong>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <span class="text-muted d-block small">Qualifying Service Period</span>
                            <strong>{{ $policy->min_service_years }} to {{ $policy->max_service_years }} Years</strong>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-muted d-block small">Calculation Base</span>
                            <strong>{{ $policy->calculation_base }}</strong>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-muted d-block small">Max Pension Cap</span>
                            <strong>{{ $policy->max_pension_percentage }}%</strong>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-muted d-block small">Monthly Pension Range</span>
                            <strong>৳{{ number_format($policy->min_pension_amount, 2) }} - ৳{{ number_format($policy->max_pension_amount, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-3"><i class="fa fa-info-circle mr-2 text-info"></i>Policy Features</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fa {{ $policy->early_retirement_allowed ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mr-2"></i> Early Retirement Allowed
                        </li>
                        <li class="mb-2">
                            <i class="fa {{ $policy->voluntary_retirement_allowed ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mr-2"></i> Voluntary Retirement Allowed
                        </li>
                        <li class="mb-2">
                            <i class="fa {{ ($policy->eligibilityRule->eligible_for_gratuity ?? false) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mr-2"></i> Gratuity Payout Enabled
                        </li>
                        <li class="mb-2">
                            <i class="fa {{ ($policy->eligibilityRule->eligible_for_commutation ?? false) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mr-2"></i> Commutation (Lump Sum) Option
                        </li>
                        <li>
                            <i class="fa {{ ($policy->eligibilityRule->eligible_for_family_pension ?? false) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mr-2"></i> Family Pension Support
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Accordion Section -->
    <div class="accordion" id="policyRulesAccordion">
        
        <!-- Section 1: Schemes Configured -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3" id="headingSchemes">
                <h5 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold text-decoration-none p-0 text-secondary" type="button" data-toggle="collapse" data-target="#collapseSchemes" aria-expanded="true">
                        <i class="fa fa-th-list text-primary mr-2"></i> Schemes Configured ({{ $policy->schemes->count() }})
                    </button>
                </h5>
            </div>
            <div id="collapseSchemes" class="collapse show" data-parent="#policyRulesAccordion">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Scheme Code</th>
                                    <th>Scheme Name</th>
                                    <th>Employee Contribution</th>
                                    <th>Employer Contribution</th>
                                    <th>Interest Rate</th>
                                    <th>Interest Calculation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($policy->schemes as $scheme)
                                <tr>
                                    <td><span class="badge badge-secondary">{{ $scheme->code }}</span></td>
                                    <td class="font-weight-bold">{{ $scheme->name }}</td>
                                    <td>{{ $scheme->employee_contribution_percentage }}%</td>
                                    <td>{{ $scheme->employer_contribution_percentage }}%</td>
                                    <td>{{ $scheme->interest_rate ?? '0.00' }}%</td>
                                    <td>{{ $scheme->interest_calculation_frequency }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Eligibility Rules -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3" id="headingEligibility">
                <h5 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold text-decoration-none p-0 collapsed text-secondary" type="button" data-toggle="collapse" data-target="#collapseEligibility">
                        <i class="fa fa-user-check text-primary mr-2"></i> Eligibility Rules
                    </button>
                </h5>
            </div>
            <div id="collapseEligibility" class="collapse" data-parent="#policyRulesAccordion">
                <div class="card-body pt-0">
                    @php $el = $policy->eligibilityRule; @endphp
                    @if($el)
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light" style="width: 250px;">Min Service Years Required</th>
                                    <td>{{ $el->min_service_years }} Years</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Max Service Years Allowed</th>
                                    <td>{{ $el->max_service_years }} Years</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Age Range Constraints</th>
                                    <td>{{ $el->min_age }} to {{ $el->max_age }} Years</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Employment Type Constraint</th>
                                    <td>{{ $el->employment_type }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light" style="width: 250px;">Employee Grades eligible</th>
                                    <td>{{ $el->employee_grade }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Gender Restrictions</th>
                                    <td>{{ $el->gender }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Must Be Permanent Status</th>
                                    <td>{{ $el->is_permanent ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Must Be Confirmed Status</th>
                                    <td>{{ $el->is_confirmed ? 'Yes' : 'No' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No eligibility rules configured.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 3: Calculation Formulas -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3" id="headingFormulas">
                <h5 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold text-decoration-none p-0 collapsed text-secondary" type="button" data-toggle="collapse" data-target="#collapseFormulas">
                        <i class="fa fa-calculator text-primary mr-2"></i> Calculation Formulas & Steps
                    </button>
                </h5>
            </div>
            <div id="collapseFormulas" class="collapse" data-parent="#policyRulesAccordion">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px;">Priority</th>
                                    <th style="width: 200px;">Code / Token</th>
                                    <th style="width: 250px;">Formula Name</th>
                                    <th>Mathematical Expression</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($policy->formulaRules()->orderBy('priority')->get() as $formula)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $formula->priority }}</td>
                                    <td><span class="badge badge-info font-weight-bold">[{{ $formula->code }}]</span></td>
                                    <td class="font-weight-bold">{{ $formula->name }}</td>
                                    <td><code class="font-weight-bold" style="font-size: 1.1rem;">{{ $formula->expression }}</code></td>
                                    <td class="text-secondary small">{{ $formula->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Commutation Rules & Age Factors -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3" id="headingCommutation">
                <h5 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold text-decoration-none p-0 collapsed text-secondary" type="button" data-toggle="collapse" data-target="#collapseCommutation">
                        <i class="fa fa-chart-pie text-primary mr-2"></i> Commutation Rules & Age Factors
                    </button>
                </h5>
            </div>
            <div id="collapseCommutation" class="collapse" data-parent="#policyRulesAccordion">
                <div class="card-body pt-0">
                    @php $com = $policy->commutationRule; @endphp
                    @if($com)
                    <div class="row">
                        <div class="col-md-5">
                            <h6 class="font-weight-bold text-secondary mb-3"><i class="fa fa-info-circle mr-1"></i>Commutation Setup</h6>
                            <table class="table table-bordered bg-white">
                                <tr>
                                    <th class="bg-light" style="width: 250px;">Enabled Status</th>
                                    <td><span class="badge badge-{{ $com->enabled ? 'success' : 'danger' }}">{{ $com->enabled ? 'Yes' : 'No' }}</span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Max Commutation Allowed</th>
                                    <td>{{ $com->max_commutation_percentage }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Default Commutation Rate</th>
                                    <td>{{ $com->default_commutation_percentage }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Calculation Mode</th>
                                    <td>{{ $com->is_age_based ? 'Age-wise factor lookup table' : 'Fixed Factor value' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <h6 class="font-weight-bold text-secondary mb-3"><i class="fa fa-table mr-1"></i>Age-wise Factor Table</h6>
                            @if($com->is_age_based && $com->factors->count() > 0)
                            <div class="row">
                                @foreach($com->factors->chunk(8) as $chunk)
                                <div class="col-md-6">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light text-center">
                                            <tr>
                                                <th>Age</th>
                                                <th>Factor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($chunk as $factor)
                                            <tr class="text-center">
                                                <td class="font-weight-bold">{{ $factor->age }} Years</td>
                                                <td><span class="badge badge-info">{{ $factor->factor }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted">No age-wise factors found. Using default factor: {{ $com->commutation_factor ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No commutation rule configured.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 5: Medical, Family, Revisions, Tax & Payments -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3" id="headingMisc">
                <h5 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold text-decoration-none p-0 collapsed text-secondary" type="button" data-toggle="collapse" data-target="#collapseMisc">
                        <i class="fa fa-cog text-primary mr-2"></i> Gratuity, Medical, Family, Tax & Payment Settings
                    </button>
                </h5>
            </div>
            <div id="collapseMisc" class="collapse" data-parent="#policyRulesAccordion">
                <div class="card-body pt-0">
                    <div class="row">
                        <!-- Gratuity & Medical -->
                        <div class="col-md-6 mb-4">
                            <h6 class="font-weight-bold text-secondary mb-2">Gratuity & Medical</h6>
                            <table class="table table-bordered bg-white">
                                <tr>
                                    <th class="bg-light" style="width: 200px;">Gratuity Enabled</th>
                                    <td>{{ ($policy->gratuityRule->enabled ?? false) ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Gratuity Formula</th>
                                    <td><code>{{ $policy->gratuityRule->formula ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Gratuity Cap</th>
                                    <td>৳{{ number_format($policy->gratuityRule->max_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Medical Allowance</th>
                                    <td>৳{{ number_format($policy->medicalRule->amount ?? 0, 2) }} ({{ $policy->medicalRule->allowance_type ?? 'Fixed' }})</td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Family & Tax -->
                        <div class="col-md-6 mb-4">
                            <h6 class="font-weight-bold text-secondary mb-2">Family Pension & Tax</h6>
                            <table class="table table-bordered bg-white">
                                <tr>
                                    <th class="bg-light" style="width: 200px;">Family Pension</th>
                                    <td>{{ ($policy->familyRule->enabled ?? false) ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Spouse / Child Rate</th>
                                    <td>Spouse: {{ $policy->familyRule->spouse_percentage ?? 100 }}%, Child: {{ $policy->familyRule->child_percentage ?? 50 }}%</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Subject to Tax</th>
                                    <td>{{ ($policy->taxRule->taxable ?? false) ? 'Yes' : 'No' }} (Rate: {{ $policy->taxRule->tax_percentage ?? 0 }}%)</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Tax Exemption</th>
                                    <td>৳{{ number_format($policy->taxRule->min_exemption ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Payment & Interest -->
                        <div class="col-md-6 mb-4">
                            <h6 class="font-weight-bold text-secondary mb-2">Payment Settings</h6>
                            <table class="table table-bordered bg-white">
                                <tr>
                                    <th class="bg-light" style="width: 200px;">Payment Frequency</th>
                                    <td>{{ $policy->paymentRule->payment_frequency ?? 'Monthly' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Disbursement Day</th>
                                    <td>Day {{ $policy->paymentRule->payment_day ?? 1 }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Payment Method</th>
                                    <td>{{ $policy->paymentRule->payment_method ?? 'EFT' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Late Payment Interest</th>
                                    <td>{{ $policy->paymentRule->late_payment_interest ?? 0 }}%</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Increments / Revisions -->
                        <div class="col-md-6 mb-4">
                            <h6 class="font-weight-bold text-secondary mb-2">Increments / Revisions</h6>
                            <table class="table table-bordered bg-white">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Revision Circular</th>
                                        <th>Increment</th>
                                        <th>Effective Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($policy->revisionRules as $rev)
                                    <tr>
                                        <td>{{ $rev->name }}<br><small class="text-muted">{{ $rev->circular_reference }}</small></td>
                                        <td class="font-weight-bold text-success">+{{ $rev->revision_percentage }}%</td>
                                        <td>{{ \Carbon\Carbon::parse($rev->effective_date)->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted small">No increments configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Version Log History -->
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body">
            <h5 class="font-weight-bold mb-4 border-bottom pb-2"><i class="fa fa-history mr-2 text-secondary"></i>Version Control History</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 text-center">
                    <thead class="bg-light text-secondary font-weight-bold">
                        <tr>
                            <th>Version</th>
                            <th>Effective Date</th>
                            <th class="text-left">Change Summary (Version Note)</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policy->versions as $version)
                        <tr>
                            <td class="align-middle font-weight-bold">
                                <span class="badge badge-info px-3 py-2">V{{ $version->version_number }}</span>
                            </td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($version->effective_date)->format('d M Y') }}</td>
                            <td class="align-middle text-left font-weight-bold text-secondary">{{ $version->change_summary }}</td>
                            <td class="align-middle">{{ $version->creator->name ?? 'System' }}</td>
                            <td class="align-middle text-muted">{{ \Carbon\Carbon::parse($version->created_at)->format('d M Y H:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No version history records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
