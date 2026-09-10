@extends('layouts.default')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 text-dark fw-bold">My Welfare Fund Statement</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('welfare.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="im im-icon-Back me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                        <i class="im im-icon-Coins fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-semibold">Total Contributed</small>
                        <h3 class="mb-0 fw-bold text-success">৳ {{ number_format($totalContributed, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                        <i class="im im-icon-Heart fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-semibold">Total Benefits / Supports Received</small>
                        <h3 class="mb-0 fw-bold text-primary">৳ {{ number_format($totalBenefitReceived, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Tabs for Statement Breakdown -->
    <ul class="nav nav-pills nav-fill bg-white p-2 rounded-3 shadow-sm mb-4" id="statementTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold py-2.5" id="contributions-tab" data-bs-toggle="tab" data-bs-target="#contributions" type="button" role="tab">
                <i class="im im-icon-Coins me-2"></i> Monthly Contributions ({{ $contributions->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2.5" id="supports-tab" data-bs-toggle="tab" data-bs-target="#supports" type="button" role="tab">
                <i class="im im-icon-Heart me-2"></i> Benefits & Supports Received ({{ $supports->count() }})
            </button>
        </li>
    </ul>

    <!-- Tab Contents Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="tab-content" id="statementTabContent">
                <!-- Contributions Tab -->
                <div class="tab-pane fade show active" id="contributions" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Month / Year</th>
                                    <th>Contribution Date</th>
                                    <th>Deducted Amount</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contributions as $index => $contrib)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold text-dark">{{ $contrib->month }} {{ $contrib->year }}</td>
                                        <td>{{ \Carbon\Carbon::parse($contrib->contribution_date)->format('d M Y') }}</td>
                                        <td class="fw-bold text-success">৳ {{ number_format($contrib->amount, 2) }}</td>
                                        <td class="text-muted">{{ $contrib->remarks ?? 'Payroll Deduction' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No contribution records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Supports Tab -->
                <div class="tab-pane fade" id="supports" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Support Category</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supports as $supp)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $supp->type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($supp->support_date)->format('d M Y') }}</td>
                                        <td class="fw-bold text-dark">৳ {{ number_format($supp->display_amount, 2) }}</td>
                                        <td>
                                            @if($supp->status == 'Approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($supp->status == 'Disbursed')
                                                <span class="badge bg-primary">Disbursed</span>
                                            @elseif($supp->status == 'Rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $supp->remarks ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No support applications found.</td>
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
