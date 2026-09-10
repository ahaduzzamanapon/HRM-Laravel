@extends('layouts.default')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 text-dark fw-bold">Welfare Fund Transaction Ledger</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('welfare.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="im im-icon-Back me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Employee Contributions</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($summary['employeeContributions'], 2) }}</h3>
                    <i class="im im-icon-Coins" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Company Contributions</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($summary['companyContributions'], 2) }}</h3>
                    <i class="im im-icon-Bank" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Total Disbursed</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($summary['totalDisbursed'], 2) }}</h3>
                    <i class="im im-icon-Money-Bag" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Net Fund Balance</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($summary['currentBalance'], 2) }}</h3>
                    <i class="im im-icon-Safe-Box" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('welfare.ledger') }}" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Month</label>
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 pt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 me-2"><i class="im im-icon-Filter me-1"></i> Filter Ledger</button>
                    <a href="{{ route('welfare.ledger') }}" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Contribution Type</th>
                            <th>Period</th>
                            <th>Credit (৳)</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contributions as $index => $contrib)
                            <tr>
                                <td>{{ $contributions->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($contrib->contribution_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $contrib->user->name ?? 'N/A' }} {{ $contrib->user->last_name ?? '' }}</span>
                                    <small class="text-muted">ID: {{ $contrib->user->emp_id ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($contrib->contribution_type == 'employee')
                                        <span class="badge bg-success-subtle text-success px-2 py-1"><i class="im im-icon-User me-1"></i> Employee Deduction</span>
                                    @elseif($contrib->contribution_type == 'company')
                                        <span class="badge bg-info-subtle text-info px-2 py-1"><i class="im im-icon-Building me-1"></i> Company Matching</span>
                                    @elseif($contrib->contribution_type == 'adjustment')
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1"><i class="im im-icon-Gear me-1"></i> Adjustment</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1"><i class="im im-icon-Close me-1"></i> Reversal</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-dark">{{ $contrib->month }} {{ $contrib->year }}</td>
                                <td class="fw-bold text-success">+ ৳ {{ number_format($contrib->amount, 2) }}</td>
                                <td class="text-muted">{{ $contrib->remarks ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No ledger transactions found matching filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {!! $contributions->links() !!}
        </div>
    </div>
</div>
@endsection
