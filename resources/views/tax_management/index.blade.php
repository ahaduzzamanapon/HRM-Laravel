@extends('layouts.default')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header Banner --}}
    <div class="card bg-primary text-white border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1"><i class="im im-icon-Calculator me-2"></i>Tax Management Module</h3>
                <p class="mb-0 opacity-75 fs-6">Manage Banking Tax Profiles, Tax Slabs, Fiscal Years, and Tax Deduction Statements</p>
            </div>
            <div>
                <span class="badge bg-light text-primary fs-6 px-3 py-2 shadow-sm fw-bold">
                    Active Fiscal Year: {{ $activeYear->year_name ?? 'Not Configured' }}
                </span>
            </div>
        </div>
    </div>

    @include('flash::message')

    {{-- Nav Tabs --}}
    <ul class="nav nav-pills nav-fill bg-white p-2 rounded-3 shadow-sm mb-4" id="taxTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold py-2" id="profiles-tab" data-bs-toggle="tab" data-bs-target="#profiles" type="button" role="tab">
                <i class="im im-icon-User me-2"></i>Employee Tax Profiles
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2" id="slabs-tab" data-bs-toggle="tab" data-bs-target="#slabs" type="button" role="tab">
                <i class="im im-icon-Bar-Chart me-2"></i>Tax Slabs Setup
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2" id="fiscal-tab" data-bs-toggle="tab" data-bs-target="#fiscal" type="button" role="tab">
                <i class="im im-icon-Calendar me-2"></i>Fiscal Year Setup
            </button>
        </li>
    </ul>

    <div class="tab-content" id="taxTabsContent">
        {{-- Tab 1: Tax Profiles --}}
        <div class="tab-pane fade show active" id="profiles" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-ID-Card me-2 text-primary"></i>Employee Tax Profiles</h5>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                        <i class="im im-icon-Add me-1"></i> Add / Configure Employee Tax Profile
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>TIN Number</th>
                                    <th>Circle / Zone</th>
                                    <th>Investment</th>
                                    <th>Rebate</th>
                                    <th>Yearly Estimate</th>
                                    <th>Monthly Tax</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxProfiles as $profile)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $profile->user->name ?? 'N/A' }} {{ $profile->user->last_name ?? '' }}</div>
                                            <small class="text-muted">ID: {{ $profile->user->emp_id ?? 'N/A' }} | {{ optional($profile->user->branch)->branch_name ?? 'N/A' }}</small>
                                        </td>
                                        <td><span class="badge bg-secondary font-monospace fs-6">{{ $profile->tin_number ?? 'N/A' }}</span></td>
                                        <td>{{ $profile->tax_circle ?? 'N/A' }} / {{ $profile->tax_zone ?? 'N/A' }}</td>
                                        <td class="fw-bold text-dark">৳ {{ number_format($profile->investment_amount, 2) }}</td>
                                        <td class="text-success fw-bold">৳ {{ number_format($profile->rebate_claimed, 2) }}</td>
                                        <td class="fw-bold text-primary">৳ {{ number_format($profile->yearly_tax_estimate, 2) }}</td>
                                        <td class="fw-bold text-danger">৳ {{ number_format($profile->monthly_tax_deduction, 2) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('taxManagement.statement', $profile->user_id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" target="_blank">
                                                <i class="im im-icon-File me-1"></i> Statement
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No Employee Tax Profiles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    {!! $taxProfiles->links() !!}
                </div>
            </div>
        </div>

        {{-- Tab 2: Tax Slabs Setup --}}
        <div class="tab-pane fade" id="slabs" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-Add me-2 text-primary"></i>New Tax Slab</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('taxManagement.slab.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Gender Category</label>
                                    <select name="gender_category" class="form-select">
                                        <option value="All">All Categories</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Senior">Senior Citizen / Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Min Income (৳)</label>
                                    <input type="number" name="min_income" class="form-control" placeholder="0" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Max Income (৳)</label>
                                    <input type="number" name="max_income" class="form-control" placeholder="350000" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tax Rate (%)</label>
                                    <input type="number" step="0.01" name="tax_rate" class="form-control" placeholder="5.00" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Fixed Base Tax (৳)</label>
                                    <input type="number" name="fixed_amount" class="form-control" placeholder="0">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="im im-icon-Check me-1"></i> Save Tax Slab</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-List me-2 text-primary"></i>Configured Tax Slabs</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Min Income</th>
                                            <th>Max Income</th>
                                            <th>Tax Rate</th>
                                            <th>Fixed Tax</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($slabs as $slab)
                                            <tr>
                                                <td><span class="badge bg-info text-dark">{{ $slab->gender_category }}</span></td>
                                                <td>৳ {{ number_format($slab->min_income, 2) }}</td>
                                                <td>৳ {{ number_format($slab->max_income, 2) }}</td>
                                                <td class="fw-bold text-primary">{{ $slab->tax_rate }} %</td>
                                                <td>৳ {{ number_format($slab->fixed_amount, 2) }}</td>
                                                <td><span class="badge bg-success">Active</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center py-4 text-muted">No tax slabs configured yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 3: Fiscal Year Setup --}}
        <div class="tab-pane fade" id="fiscal" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-Calendar me-2 text-primary"></i>Add Fiscal Year</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('taxManagement.fiscalYear.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Year Title</label>
                                    <input type="text" name="year_name" class="form-control" placeholder="e.g. FY 2025-2026" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">End Date</label>
                                    <input type="date" name="end_date" class="form-control" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                                    <label class="form-check-label fw-semibold" for="is_active">Set as Active Fiscal Year</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="im im-icon-Check me-1"></i> Save Fiscal Year</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-Clock me-2 text-primary"></i>Fiscal Years</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Year Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fiscalYears as $fy)
                                        <tr>
                                            <td class="fw-bold">{{ $fy->year_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($fy->start_date)->format('d M, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($fy->end_date)->format('d M, Y') }}</td>
                                            <td>
                                                @if($fy->is_active)
                                                    <span class="badge bg-success"><i class="im im-icon-Yes me-1"></i> Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted">No fiscal years defined.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Tax Profile Modal --}}
<div class="modal fade" id="addProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="im im-icon-User me-2"></i>Configure Employee Tax Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('taxManagement.profile.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Employee</label>
                        <select name="user_id" class="form-select select2-tax-modal" required>
                            <option value="">-- Choose Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} {{ $emp->last_name }} {{ $emp->emp_id ? '(ID: '.$emp->emp_id.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">TIN Number</label>
                            <input type="text" name="tin_number" class="form-control" placeholder="12-digit TIN">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tax Circle</label>
                            <input type="text" name="tax_circle" class="form-control" placeholder="Circle-12">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tax Zone</label>
                            <input type="text" name="tax_zone" class="form-control" placeholder="Zone-05">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Monthly Gross Salary (৳)</label>
                            <input type="number" name="monthly_gross" class="form-control" placeholder="50000" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Approved Investments (৳)</label>
                        <input type="number" name="investment_amount" class="form-control" placeholder="DPS, Insurance, PF, stocks">
                        <small class="text-muted">15% tax rebate will be calculated automatically</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="im im-icon-Check me-1"></i> Save Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addProfileModal').on('shown.bs.modal', function () {
            $('.select2-tax-modal').select2({
                dropdownParent: $('#addProfileModal'),
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Choose Employee --'
            });
        });
    });
</script>
@endpush
@endsection
