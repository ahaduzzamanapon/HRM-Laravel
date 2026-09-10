@extends('layouts.default')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 text-dark fw-bold">Welfare Fund Dashboard</h2>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('welfare.myStatement') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="im im-icon-File-TXT me-1"></i> My Statement
            </a>
            <a href="{{ route('medicalSupports.create') }}" class="btn btn-primary rounded-pill px-3">
                <i class="im im-icon-Add me-1"></i> New Application
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Total Fund Collected</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($totalCollected, 2) }}</h3>
                    <i class="im im-icon-Money-Bag" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Total Disbursed</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($totalDisbursed, 2) }}</h3>
                    <i class="im im-icon-Coins" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Net Fund Balance</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">৳ {{ number_format($netFundBalance, 2) }}</h3>
                    <i class="im im-icon-Safe-Box" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="c_card h-100 p-3" style="border-radius: 12px; background: #f4f9ff; border: 1px solid #e1eeff; box-shadow: 0px 4px 12px rgba(1, 119, 188, 0.08); color: #0177bc;">
                <h6 class="mb-2 fw-semibold" style="font-size: 15px !important; color: #334155;">Pending Applications</h6>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h3 class="mb-0 fw-bold" style="color: #0177bc;">{{ $totalPending }}</h3>
                    <i class="im im-icon-Clock" style="font-size: 36px; color: #0177bc;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown & Recent Applications -->
    <div class="row g-4">
        <!-- Recent Support Applications -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-File-TXT text-primary me-2"></i>Recent Applications</h5>
                    <span class="badge bg-light text-dark">{{ $recentApplications->count() }} Records</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Support Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $app)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $app->employee->name ?? 'N/A' }} {{ $app->employee->last_name ?? '' }}</span>
                                            <small class="text-muted">ID: {{ $app->employee->emp_id ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($app->type == 'Medical Support')
                                                <span class="badge bg-info-subtle text-info px-2 py-1"><i class="im im-icon-Medical-Sign me-1"></i> Medical</span>
                                            @elseif($app->type == 'Funeral Support')
                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1"><i class="im im-icon-Coffin me-1"></i> Funeral</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success px-2 py-1"><i class="im im-icon-Student-Female me-1"></i> Education</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">৳ {{ number_format($app->display_amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($app->support_date)->format('d M Y') }}</td>
                                        <td>
                                            @if($app->status == 'Approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($app->status == 'Disbursed')
                                                <span class="badge bg-primary">Disbursed</span>
                                            @elseif($app->status == 'Rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($app->type == 'Medical Support')
                                                <a href="{{ route('medicalSupports.show', $app->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="im im-icon-Eye"></i></a>
                                            @elseif($app->type == 'Funeral Support')
                                                <a href="{{ route('funeralSupports.show', $app->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="im im-icon-Eye"></i></a>
                                            @else
                                                <a href="{{ route('employeeChildrenEducationSupports.show', $app->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="im im-icon-Eye"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No recent welfare applications.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Monthly Contributions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-Coins text-success me-2"></i>Recent Payroll Deductions</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentContributions as $contrib)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="fw-semibold text-dark d-block" style="font-size: 13.5px;">{{ $contrib->user->name ?? 'N/A' }} {{ $contrib->user->last_name ?? '' }}</span>
                                    <small class="text-muted" style="font-size: 11.5px;">{{ $contrib->month }} {{ $contrib->year }}</small>
                                </div>
                                <span class="badge bg-success-subtle text-success fs-6 fw-bold">+ ৳ {{ number_format($contrib->amount, 2) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-4 text-muted">No contribution records yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
