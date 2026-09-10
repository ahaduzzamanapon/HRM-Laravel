@extends('layouts.default')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 text-dark fw-bold">Welfare Fund Reports & Analytics</h2>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('welfare.reports', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-outline-danger rounded-pill px-3">
                <i class="im im-icon-File-PDF me-1"></i> Export PDF
            </a>
            <a href="{{ route('welfare.reports', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-outline-success rounded-pill px-3">
                <i class="im im-icon-File-Excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('welfare.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="im im-icon-Back me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('welfare.reports') }}" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Year</label>
                    <select name="year" class="form-select">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Month</label>
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 pt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 me-2"><i class="im im-icon-Filter me-1"></i> Generate Report</button>
                    <a href="{{ route('welfare.reports') }}" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Nav Tabs for Reports -->
    <ul class="nav nav-pills nav-fill bg-white p-2 rounded-3 shadow-sm mb-4" id="reportTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold py-2.5" id="contrib-report-tab" data-bs-toggle="tab" data-bs-target="#contrib-report" type="button" role="tab">
                <i class="im im-icon-Coins me-2"></i> Contribution Report (৳ {{ number_format($contributions->sum('amount'), 2) }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2.5" id="disburse-report-tab" data-bs-toggle="tab" data-bs-target="#disburse-report" type="button" role="tab">
                <i class="im im-icon-Heart me-2"></i> Disbursement Report (৳ {{ number_format($disbursements->sum('approved_amount'), 2) }})
            </button>
        </li>
    </ul>

    <!-- Tab Contents Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="tab-content" id="reportTabContent">
                <!-- Contribution Report -->
                <div class="tab-pane fade show active" id="contrib-report" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Contribution Type</th>
                                    <th>Period</th>
                                    <th>Date</th>
                                    <th>Amount (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contributions as $index => $c)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $c->user->name ?? 'N/A' }} {{ $c->user->last_name ?? '' }}</span>
                                            <small class="text-muted">ID: {{ $c->user->emp_id ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($c->contribution_type == 'employee')
                                                <span class="badge bg-success-subtle text-success">Employee</span>
                                            @elseif($c->contribution_type == 'company')
                                                <span class="badge bg-info-subtle text-info">Company Matching</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($c->contribution_type) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $c->month }} {{ $c->year }}</td>
                                        <td>{{ \Carbon\Carbon::parse($c->contribution_date)->format('d M Y') }}</td>
                                        <td class="fw-bold text-success">৳ {{ number_format($c->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No contribution records for selected filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Disbursement Report -->
                <div class="tab-pane fade" id="disburse-report" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Support Category</th>
                                    <th>Application Date</th>
                                    <th>Approved Amount</th>
                                    <th>Disbursed Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disbursements as $index => $d)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $d->employee->name ?? 'N/A' }} {{ $d->employee->last_name ?? '' }}</span>
                                            <small class="text-muted">ID: {{ $d->employee->emp_id ?? 'N/A' }}</small>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $d->support_category }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->support_date)->format('d M Y') }}</td>
                                        <td class="fw-bold text-dark">৳ {{ number_format($d->approved_amount ?? $d->amount ?? $d->financial_assistance, 2) }}</td>
                                        <td class="fw-bold text-primary">৳ {{ number_format($d->disbursed_amount ?? $d->approved_amount ?? $d->amount ?? $d->financial_assistance, 2) }}</td>
                                        <td>
                                            @if($d->status == 'Disbursed')
                                                <span class="badge bg-primary">Disbursed</span>
                                            @else
                                                <span class="badge bg-success">Approved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No disbursement records for selected filters.</td>
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
@endsection
