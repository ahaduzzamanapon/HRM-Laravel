@extends('layouts.default')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header Banner --}}
    <div class="card bg-success text-white border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1"><i class="im im-icon-Coins me-2"></i>Employee Loan Management</h3>
                <p class="mb-0 opacity-75 fs-6">Manage Staff Loans, Approval Workflows, Disbursement & EMI Repayments</p>
            </div>
            <div>
                <a href="{{ route('employeeLoans.create') }}" class="btn btn-light text-success fw-bold rounded-pill px-4 shadow-sm">
                    <i class="im im-icon-Add me-1"></i> Apply For Loan
                </a>
            </div>
        </div>
    </div>

    @include('flash::message')

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="badge bg-primary-subtle text-primary p-3 rounded-circle me-3">
                        <i class="im im-icon-Coins fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Applications</small>
                        <h4 class="fw-bold mb-0">{{ $loans->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="badge bg-warning-subtle text-warning p-3 rounded-circle me-3">
                        <i class="im im-icon-Clock fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Pending Approval</small>
                        <h4 class="fw-bold mb-0 text-warning">{{ $loans->where('status', 'Pending')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="badge bg-success-subtle text-success p-3 rounded-circle me-3">
                        <i class="im im-icon-Yes fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Disbursed Loans</small>
                        <h4 class="fw-bold mb-0 text-success">{{ $loans->where('status', 'Disbursed')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="badge bg-danger-subtle text-danger p-3 rounded-circle me-3">
                        <i class="im im-icon-Close fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Rejected / Cancelled</small>
                        <h4 class="fw-bold mb-0 text-danger">{{ $loans->where('status', 'Rejected')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Loans Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><i class="im im-icon-List me-2 text-success"></i>Loan Applications List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Application No</th>
                            <th>Employee</th>
                            <th>Loan Type</th>
                            <th>Principal Amount</th>
                            <th>Interest Rate</th>
                            <th>Installments</th>
                            <th>Monthly EMI</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td><span class="badge bg-light text-dark font-monospace border fs-6">{{ $loan->application_no ?? 'LN-'.$loan->id }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $loan->employee->name ?? 'N/A' }} {{ $loan->employee->last_name ?? '' }}</div>
                                    <small class="text-muted">{{ optional($loan->employee->branch)->branch_name ?? 'N/A' }}</small>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $loan->loanType->name ?? 'Staff Loan' }}</span></td>
                                <td class="fw-bold text-dark">৳ {{ number_format($loan->amount, 2) }}</td>
                                <td class="fw-bold text-primary">{{ $loan->interest_rate }} %</td>
                                <td>{{ $loan->installments }} months</td>
                                <td class="fw-bold text-danger">৳ {{ number_format($loan->monthly_installment, 2) }}</td>
                                <td>
                                    @if($loan->status === 'Pending')
                                        <span class="badge bg-warning text-dark"><i class="im im-icon-Clock me-1"></i> Pending</span>
                                    @elseif($loan->status === 'Approved')
                                        <span class="badge bg-info"><i class="im im-icon-Check me-1"></i> Approved</span>
                                    @elseif($loan->status === 'Disbursed')
                                        <span class="badge bg-success"><i class="im im-icon-Yes me-1"></i> Disbursed</span>
                                    @else
                                        <span class="badge bg-danger"><i class="im im-icon-Close me-1"></i> Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="action-buttons-group">
                                        <a href="{{ route('employeeLoans.show', $loan->id) }}" class="btn-action btn-action-view" title="View" data-bs-toggle="tooltip">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        @if(($loan->employee_id == auth()->id() && strtolower($loan->status) === 'pending') || $canManageLoans)
                                            <a href="{{ route('employeeLoans.edit', $loan->id) }}" class="btn-action btn-action-edit" title="Edit" data-bs-toggle="tooltip">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endif

                                        @if($canManageLoans && strtolower($loan->status) === 'pending')
                                            <form action="{{ route('employeeLoans.approve', $loan->id) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Approve this loan application?')">
                                                @csrf
                                                <button type="submit" class="btn-action btn-action-approve" title="Approve" data-bs-toggle="tooltip">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('employeeLoans.reject', $loan->id) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Reject this loan application?')">
                                                @csrf
                                                <button type="submit" class="btn-action btn-action-reject" title="Reject" data-bs-toggle="tooltip">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No loan applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {!! $loans->links() !!}
        </div>
    </div>
</div>
@endsection
