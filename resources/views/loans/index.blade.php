@extends('layouts.default')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header Banner --}}
    <div class="card border-0 shadow-sm mb-4 rounded-3" style="background: aliceblue; border: 1px solid #cce5ff;">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0177bc;"><i class="im im-icon-Coins me-2"></i>Employee Loan Management</h3>
                <p class="mb-0 text-muted fs-6">Manage Staff Loans, Payroll Effective Month Deductions & Repayment History</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" style="background-color: #0177bc; border-color: #0177bc;" data-bs-toggle="modal" data-bs-target="#applyLoanModal" data-toggle="modal" data-target="#applyLoanModal">
                    <i class="im im-icon-Add me-1"></i> Apply For Loan
                </button>
            </div>
        </div>
    </div>

    @include('flash::message')

    {{-- Dashboard Summary Cards --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4 align-items-stretch">
        <div class="col">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex flex-column justify-content-between h-100">
                    <small class="text-muted fw-semibold mb-2 d-block">Active Loans</small>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge p-2 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e6f0fa; color: #0177bc; width: 42px; height: 42px; flex-shrink: 0;">
                            <i class="im im-icon-Coins fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-primary">{{ $metrics['totalActiveLoans'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex flex-column justify-content-between h-100">
                    <small class="text-muted fw-semibold mb-2 d-block">Pending Disbursed</small>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge p-2 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #fff3cd; color: #ffc107; width: 42px; height: 42px; flex-shrink: 0;">
                            <i class="im im-icon-Clock fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-warning">{{ $metrics['pendingDisbursementCount'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex flex-column justify-content-between h-100">
                    <small class="text-muted fw-semibold mb-2 d-block">Starting Next Month</small>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge p-2 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #cff4fc; color: #0dcaf0; width: 42px; height: 42px; flex-shrink: 0;">
                            <i class="im im-icon-Calendar fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-info">{{ $metrics['loansStartingNextMonth'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex flex-column justify-content-between h-100">
                    <small class="text-muted fw-semibold mb-2 d-block">Total Outstanding</small>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge p-2 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #f8d7da; color: #dc3545; width: 42px; height: 42px; flex-shrink: 0;">
                            <i class="im im-icon-Money-Bag fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-danger" style="font-size: 1.05rem; white-space: nowrap;">৳ {{ number_format($metrics['totalOutstandingAmount'] ?? 0, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex flex-column justify-content-between h-100">
                    <small class="text-muted fw-semibold mb-2 d-block">Monthly Recovery</small>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge p-2 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #d1e7dd; color: #198754; width: 42px; height: 42px; flex-shrink: 0;">
                            <i class="im im-icon-Calculator fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-success" style="font-size: 1.05rem; white-space: nowrap;">৳ {{ number_format($metrics['totalMonthlyDeductions'] ?? 0, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    @if($canManageLoans)
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <form action="{{ route('employeeLoans.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Disbursed" {{ request('status') == 'Disbursed' ? 'selected' : '' }}>Disbursed</option>
                        <option value="Active Repayment" {{ request('status') == 'Active Repayment' ? 'selected' : '' }}>Active Repayment</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Required Month</label>
                    <input type="month" name="required_month" class="form-control form-control-sm" value="{{ request('required_month') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Effective Month</label>
                    <input type="month" name="effective_month" class="form-control form-control-sm" value="{{ request('effective_month') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3"><i class="im im-icon-Filter me-1"></i> Filter</button>
                    <a href="{{ route('employeeLoans.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
                </div>
            </form>
        </div>
    </div>
    @endif

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
                            <th>Category</th>
                            <th>Requested Amount</th>
                            <th>Installment Term</th>
                            <th>Monthly EMI</th>
                            <th>Required Month</th>
                            <th>Effective Month</th>
                            <th>Outstanding</th>
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
                                <td><span class="badge bg-success fs-6 fw-bold p-2">৳ {{ number_format($loan->amount, 2) }}</span></td>
                                <td><span class="badge bg-dark fs-6 fw-bold p-2"><i class="fa fa-calendar me-1"></i>{{ round($loan->installments / 12, 1) }} Yrs ({{ $loan->installments }} Mon)</span></td>
                                <td class="fw-bold text-danger">৳ {{ number_format($loan->monthly_installment, 2) }}</td>
                                <td><small class="fw-semibold text-secondary">{{ $loan->required_month ? \Carbon\Carbon::parse($loan->required_month)->format('M Y') : '—' }}</small></td>
                                <td><small class="fw-bold text-primary">{{ $loan->effective_month ? \Carbon\Carbon::parse($loan->effective_month)->format('M Y') : '—' }}</small></td>
                                <td class="fw-bold text-dark">৳ {{ number_format($loan->outstanding_balance, 2) }}</td>
                                <td>
                                    @if($loan->status === 'Pending')
                                        <span class="badge bg-warning text-dark"><i class="im im-icon-Clock me-1"></i> Pending</span>
                                    @elseif($loan->status === 'Approved')
                                        <span class="badge bg-info"><i class="im im-icon-Check me-1"></i> Approved</span>
                                    @elseif($loan->status === 'Disbursed')
                                        <span class="badge bg-success"><i class="im im-icon-Yes me-1"></i> Disbursed</span>
                                    @elseif($loan->status === 'Active Repayment')
                                        <span class="badge bg-primary"><i class="im im-icon-Sync me-1"></i> Repaying</span>
                                    @elseif($loan->status === 'Completed')
                                        <span class="badge bg-dark"><i class="im im-icon-Ok me-1"></i> Completed</span>
                                    @else
                                        <span class="badge bg-danger"><i class="im im-icon-Close me-1"></i> {{ $loan->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="action-buttons-group">
                                        <a href="{{ route('employeeLoans.show', $loan->id) }}" class="btn-action btn-action-view" title="View" data-bs-toggle="tooltip">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        @if((($loan->employee_id == auth()->id() && strtolower($loan->status) === 'pending') || $canManageLoans) && !in_array(strtolower($loan->status), ['disbursed', 'active repayment', 'repaying', 'completed']))
                                            <button type="button" class="btn-action btn-action-edit" title="Edit" data-bs-toggle="tooltip" onclick='openEditLoanModal({{ json_encode($loan) }})'>
                                                <i class="fa fa-pencil"></i>
                                            </button>
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
                                <td colspan="11" class="text-center py-4 text-muted">No loan applications found.</td>
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

<style>
    /* Loan Application Form Text & Input Color Styling */
    #applyLoanModal .form-label,
    #editLoanModal .form-label,
    #loanApplicationForm .form-label,
    #loanEditForm .form-label,
    #editLoanForm .form-label {
        color: #000000 !important;
        font-weight: 600 !important;
    }

    #applyLoanModal .form-text,
    #editLoanModal .form-text,
    #loanApplicationForm .form-text,
    #loanEditForm .form-text,
    #editLoanForm .form-text {
        color: #333333 !important;
    }

    #applyLoanModal .form-control,
    #applyLoanModal .form-select,
    #editLoanModal .form-control,
    #editLoanModal .form-select,
    #loanApplicationForm .form-control,
    #loanApplicationForm .form-select,
    #loanEditForm .form-control,
    #loanEditForm .form-select,
    #editLoanForm .form-control,
    #editLoanForm .form-select {
        color: #000000 !important;
        font-weight: 500 !important;
    }

    /* Muted placeholder style */
    #applyLoanModal .form-control::placeholder,
    #applyLoanModal textarea::placeholder,
    #editLoanModal .form-control::placeholder,
    #editLoanModal textarea::placeholder,
    #loanApplicationForm .form-control::placeholder,
    #loanApplicationForm textarea::placeholder,
    #loanEditForm .form-control::placeholder,
    #loanEditForm textarea::placeholder,
    #editLoanForm .form-control::placeholder,
    #editLoanForm textarea::placeholder {
        color: #6c757d !important;
        opacity: 0.8 !important;
        font-weight: 400 !important;
    }

    #applyLoanModal .form-control::-webkit-input-placeholder,
    #editLoanModal .form-control::-webkit-input-placeholder,
    #loanApplicationForm .form-control::-webkit-input-placeholder,
    #loanEditForm .form-control::-webkit-input-placeholder,
    #editLoanForm .form-control::-webkit-input-placeholder {
        color: #6c757d !important;
        opacity: 0.8 !important;
        font-weight: 400 !important;
    }

    /* Select2 dropdown text & placeholder */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000000 !important;
        font-weight: 500 !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder,
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d !important;
        font-weight: 400 !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option,
    .select2-container--default .select2-dropdown .select2-results__option {
        color: #000000 !important;
    }
</style>

<!-- Apply For Loan Modal -->
<div class="modal fade" id="applyLoanModal" tabindex="-1" aria-labelledby="applyLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-3" style="background: aliceblue; border-bottom: 1px solid #cce5ff;">
                <h5 class="modal-title fw-bold mb-0" id="applyLoanModalLabel" style="color: #0177bc;">
                    <i class="im im-icon-Coins me-2"></i>Apply For Employee Loan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employeeLoans.store') }}" method="POST" id="loanApplicationModalForm">
                @csrf
                <div class="modal-body p-4 text-start">
                    @if(!empty($canManageLoans) && $canManageLoans && !empty($employees) && count($employees) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Employee</label>
                            <select name="employee_id" id="modal_employee_id" class="form-select select2-modal-loan-search">
                                <option value="" data-grade="{{ auth()->user()->salaryGrade->grade ?? '' }}">-- Apply for Self / Select Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" 
                                            data-grade="{{ $emp->salaryGrade->grade ?? '' }}"
                                            {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} {{ $emp->last_name }} (ID: {{ $emp->emp_id }}) {{ $emp->salaryGrade ? '['.$emp->salaryGrade->grade.']' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Loan Category / Type</label>
                        <select name="loan_type_id" id="modal_loan_type_id" class="form-select" required>
                            <option value="">-- Select Loan Type --</option>
                            @foreach($loanTypes as $type)
                                <option value="{{ $type->id }}" 
                                        data-rate="{{ $type->interest_rate ?? 5 }}" 
                                        data-max="{{ $type->max_installments ?? '' }}"
                                        data-ceilings="{{ json_encode($type->loan_ceilings ?? []) }}"
                                        {{ old('loan_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} (Rate: {{ $type->interest_rate ?? 5 }}%, Max Term: {{ $type->max_installments ? $type->max_installments.' Months' : 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Requested Amount (৳)</label>
                            <input type="number" name="amount" id="modal_amount" class="form-control" placeholder="100000" min="1000" value="{{ old('amount') }}" required>
                            <div id="modal_amountLimitInfo" class="form-text mt-1 text-primary fw-medium"></div>
                            <div id="modal_amountWarning" class="invalid-feedback fw-bold mt-1"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Installment Months</label>
                            <input type="number" name="installments" id="modal_installments" class="form-control" placeholder="24" min="1" value="{{ old('installments') }}" required>
                            <div id="modal_installmentsLimitInfo" class="form-text mt-1 text-primary fw-medium"></div>
                            <div id="modal_installmentsWarning" class="invalid-feedback fw-bold mt-1"></div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loan Required Month <span class="text-danger">*</span></label>
                            <input type="month" name="required_month" id="modal_required_month" class="form-control" value="{{ old('required_month', date('Y-m')) }}" required>
                            <div class="form-text text-muted">Month when loan disbursement is needed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loan Effective Month <span class="text-danger">*</span></label>
                            <input type="month" name="effective_month" id="modal_effective_month" class="form-control" value="{{ old('effective_month', date('Y-m', strtotime('+1 month'))) }}" required>
                            <div class="form-text text-muted">First payroll month for salary deduction.</div>
                            <div id="modal_effectiveMonthWarning" class="invalid-feedback fw-bold mt-1"></div>
                        </div>
                    </div>

                    {{-- Dynamic EMI Preview --}}
                    <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4" id="modal_emiPreview" style="display:none;">
                        <h6 class="fw-bold text-primary mb-2"><i class="im im-icon-Calculator me-1"></i> Estimated EMI Calculation</h6>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <small class="text-muted d-block">Principal</small>
                                <strong id="modal_previewPrincipal">৳ 0</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Est. Interest</small>
                                <strong id="modal_previewInterest">৳ 0</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Monthly EMI</small>
                                <strong class="text-danger fs-5" id="modal_previewEMI">৳ 0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Purpose / Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Reason for loan application...">{{ old('remarks') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="modal_submitBtn" class="btn btn-primary rounded-pill px-4" style="background-color: #0177bc; border-color: #0177bc;">
                        <i class="im im-icon-Check me-1"></i> Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Loan Modal -->
<div class="modal fade" id="editLoanModal" tabindex="-1" aria-labelledby="editLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-3" style="background: aliceblue; border-bottom: 1px solid #cce5ff;">
                <h5 class="modal-title fw-bold mb-0" id="editLoanModalLabel" style="color: #0177bc;">
                    <i class="im im-icon-Edit me-2"></i>Edit Loan Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editLoanForm">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-start">
                    @if(!empty($canManageLoans) && $canManageLoans && !empty($employees) && count($employees) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Employee</label>
                            <select name="employee_id" id="edit_employee_id" class="form-select select2-edit-loan-search">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" 
                                            data-grade="{{ $emp->salaryGrade->grade ?? '' }}">
                                        {{ $emp->name }} {{ $emp->last_name }} (ID: {{ $emp->emp_id }}) {{ $emp->salaryGrade ? '['.$emp->salaryGrade->grade.']' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Loan Category / Type</label>
                        <select name="loan_type_id" id="edit_loan_type_id" class="form-select" required>
                            <option value="">-- Select Loan Type --</option>
                            @foreach($loanTypes as $type)
                                <option value="{{ $type->id }}" 
                                        data-rate="{{ $type->interest_rate ?? 5 }}" 
                                        data-max="{{ $type->max_installments ?? '' }}"
                                        data-ceilings="{{ json_encode($type->loan_ceilings ?? []) }}">
                                    {{ $type->name }} (Rate: {{ $type->interest_rate ?? 5 }}%, Max Term: {{ $type->max_installments ? $type->max_installments.' Months' : 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Requested Amount (৳)</label>
                            <input type="number" name="amount" id="edit_amount" class="form-control" min="1000" required>
                            <div id="edit_amountLimitInfo" class="form-text mt-1 text-primary fw-medium"></div>
                            <div id="edit_amountWarning" class="invalid-feedback fw-bold mt-1"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Installment Months</label>
                            <input type="number" name="installments" id="edit_installments" class="form-control" min="1" required>
                            <div id="edit_installmentsLimitInfo" class="form-text mt-1 text-primary fw-medium"></div>
                            <div id="edit_installmentsWarning" class="invalid-feedback fw-bold mt-1"></div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loan Required Month <span class="text-danger">*</span></label>
                            <input type="month" name="required_month" id="edit_required_month" class="form-control" required>
                            <div class="form-text text-muted">Month when loan disbursement is needed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loan Effective Month <span class="text-danger">*</span></label>
                            <input type="month" name="effective_month" id="edit_effective_month" class="form-control" required>
                            <div class="form-text text-muted">First payroll month for salary deduction.</div>
                            <div id="edit_effectiveMonthWarning" class="invalid-feedback fw-bold mt-1"></div>
                        </div>
                    </div>

                    {{-- Dynamic EMI Preview --}}
                    <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4" id="edit_emiPreview" style="display:none;">
                        <h6 class="fw-bold text-primary mb-2"><i class="im im-icon-Calculator me-1"></i> Estimated EMI Calculation</h6>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <small class="text-muted d-block">Principal</small>
                                <strong id="edit_previewPrincipal">৳ 0</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Est. Interest</small>
                                <strong id="edit_previewInterest">৳ 0</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Monthly EMI</small>
                                <strong class="text-danger fs-5" id="edit_previewEMI">৳ 0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Purpose / Remarks</label>
                        <textarea name="remarks" id="edit_remarks" class="form-control" rows="3" placeholder="Reason for loan application..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="edit_submitBtn" class="btn btn-primary rounded-pill px-4" style="background-color: #0177bc; border-color: #0177bc;">
                        <i class="im im-icon-Check me-1"></i> Update Application
                    </button>
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
    window.authGrade = "{{ auth()->user()->salaryGrade->grade ?? '' }}";

    $(document).ready(function() {
        if ($('.select2-modal-loan-search').length) {
            $('.select2-modal-loan-search').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#applyLoanModal'),
                placeholder: '-- Apply for Self / Select Employee --'
            });
        }
        if ($('.select2-edit-loan-search').length) {
            $('.select2-edit-loan-search').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#editLoanModal')
            });
        }

        @if($errors->any())
            if (typeof $ !== 'undefined' && typeof $('#applyLoanModal').modal === 'function') {
                $('#applyLoanModal').modal('show');
            } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var bsModal = new bootstrap.Modal(document.getElementById('applyLoanModal'));
                bsModal.show();
            }
        @endif

        validateModalCategoryLimits();
    });

    function openEditLoanModal(loan) {
        var updateUrl = "{{ url('/employee-loans') }}/" + loan.id;
        $('#editLoanForm').attr('action', updateUrl);

        if ($('#edit_employee_id').length) {
            $('#edit_employee_id').val(loan.employee_id).trigger('change');
        }

        $('#edit_loan_type_id').val(loan.loan_type_id).trigger('change');
        $('#edit_amount').val(loan.amount);
        $('#edit_installments').val(loan.installments);

        if (loan.required_month) {
            var reqM = loan.required_month.substring(0, 7);
            $('#edit_required_month').val(reqM);
        }
        if (loan.effective_month) {
            var effM = loan.effective_month.substring(0, 7);
            $('#edit_effective_month').val(effM);
        }
        $('#edit_remarks').val(loan.remarks || '');

        validateEditModalCategoryLimits();

        if (typeof $ !== 'undefined' && typeof $('#editLoanModal').modal === 'function') {
            $('#editLoanModal').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var bsModal = new bootstrap.Modal(document.getElementById('editLoanModal'));
            bsModal.show();
        }
    }

    function validateModalCategoryLimits() {
        const selectedType = $('#modal_loan_type_id option:selected');
        const typeId = $('#modal_loan_type_id').val();
        const amt = parseFloat($('#modal_amount').val()) || 0;
        const months = parseInt($('#modal_installments').val()) || 0;
        const reqMonth = $('#modal_required_month').val();
        const effMonth = $('#modal_effective_month').val();

        $('#modal_amountWarning, #modal_installmentsWarning, #modal_effectiveMonthWarning').html('');
        $('#modal_amount, #modal_installments, #modal_effective_month').removeClass('is-invalid');

        if (reqMonth && effMonth && effMonth < reqMonth) {
            $('#modal_effective_month').addClass('is-invalid');
            $('#modal_effectiveMonthWarning').html('⚠️ Loan Effective Month cannot be earlier than Loan Required Month!');
        }

        if (!typeId || !selectedType.length) {
            $('#modal_amountLimitInfo, #modal_installmentsLimitInfo').hide().html('');
            return;
        }

        const maxTerm = parseInt(selectedType.data('max')) || 0;
        let rawCeilings = selectedType.data('ceilings');
        if (typeof rawCeilings === 'string') {
            try { rawCeilings = JSON.parse(rawCeilings); } catch(e) { rawCeilings = []; }
        }

        if (maxTerm > 0) {
            $('#modal_installments').attr('max', maxTerm);
            $('#modal_installmentsLimitInfo').html('<i class="im im-icon-Information me-1"></i> Category Max Term: <strong>' + maxTerm + ' Months</strong>').show();
            
            if (months > maxTerm) {
                $('#modal_installments').addClass('is-invalid');
                $('#modal_installmentsWarning').html('⚠️ Entered installments (' + months + ' months) exceeds maximum limit of ' + maxTerm + ' months for this category!');
            }
        } else {
            $('#modal_installments').removeAttr('max');
            $('#modal_installmentsLimitInfo').hide().html('');
        }

        let selectedEmpOption = $('#modal_employee_id option:selected');
        let empGrade = (selectedEmpOption.length && $('#modal_employee_id').val()) 
            ? (selectedEmpOption.data('grade') || '') 
            : window.authGrade;

        let maxCeiling = null;
        let ceilingGradeLabel = null;

        if (Array.isArray(rawCeilings) && rawCeilings.length > 0) {
            if (empGrade) {
                for (let i = 0; i < rawCeilings.length; i++) {
                    let c = rawCeilings[i];
                    if (c && c.grade && c.amount) {
                        let cG = String(c.grade).trim().toLowerCase().replace('grade ', '');
                        let eG = String(empGrade).trim().toLowerCase().replace('grade ', '');
                        if (cG === eG) {
                            maxCeiling = parseFloat(c.amount);
                            ceilingGradeLabel = c.grade;
                            break;
                        }
                    }
                }
            }

            if (maxCeiling === null) {
                for (let i = 0; i < rawCeilings.length; i++) {
                    let a = parseFloat(rawCeilings[i].amount);
                    if (!isNaN(a) && (maxCeiling === null || a > maxCeiling)) {
                        maxCeiling = a;
                    }
                }
            }
        }

        if (maxCeiling !== null && maxCeiling > 0) {
            let gradeInfoStr = ceilingGradeLabel ? (' for ' + ceilingGradeLabel) : (empGrade ? (' for ' + empGrade) : '');
            $('#modal_amountLimitInfo').html('<i class="im im-icon-Information me-1"></i> Max Loan Ceiling' + gradeInfoStr + ': <strong>৳ ' + maxCeiling.toLocaleString() + '</strong>').show();

            if (amt > maxCeiling) {
                $('#modal_amount').addClass('is-invalid');
                $('#modal_amountWarning').html('⚠️ Requested amount (৳ ' + amt.toLocaleString() + ') exceeds maximum ceiling limit of ৳ ' + maxCeiling.toLocaleString() + gradeInfoStr + '!');
            }
        } else {
            $('#modal_amountLimitInfo').hide().html('');
        }
    }

    function validateEditModalCategoryLimits() {
        const selectedType = $('#edit_loan_type_id option:selected');
        const typeId = $('#edit_loan_type_id').val();
        const amt = parseFloat($('#edit_amount').val()) || 0;
        const months = parseInt($('#edit_installments').val()) || 0;
        const reqMonth = $('#edit_required_month').val();
        const effMonth = $('#edit_effective_month').val();

        $('#edit_amountWarning, #edit_installmentsWarning, #edit_effectiveMonthWarning').html('');
        $('#edit_amount, #edit_installments, #edit_effective_month').removeClass('is-invalid');

        if (reqMonth && effMonth && effMonth < reqMonth) {
            $('#edit_effective_month').addClass('is-invalid');
            $('#edit_effectiveMonthWarning').html('⚠️ Loan Effective Month cannot be earlier than Loan Required Month!');
        }

        if (!typeId || !selectedType.length) {
            $('#edit_amountLimitInfo, #edit_installmentsLimitInfo').hide().html('');
            return;
        }

        const maxTerm = parseInt(selectedType.data('max')) || 0;
        let rawCeilings = selectedType.data('ceilings');
        if (typeof rawCeilings === 'string') {
            try { rawCeilings = JSON.parse(rawCeilings); } catch(e) { rawCeilings = []; }
        }

        if (maxTerm > 0) {
            $('#edit_installments').attr('max', maxTerm);
            $('#edit_installmentsLimitInfo').html('<i class="im im-icon-Information me-1"></i> Category Max Term: <strong>' + maxTerm + ' Months</strong>').show();
            
            if (months > maxTerm) {
                $('#edit_installments').addClass('is-invalid');
                $('#edit_installmentsWarning').html('⚠️ Entered installments (' + months + ' months) exceeds maximum limit of ' + maxTerm + ' months for this category!');
            }
        } else {
            $('#edit_installments').removeAttr('max');
            $('#edit_installmentsLimitInfo').hide().html('');
        }

        let selectedEmpOption = $('#edit_employee_id option:selected');
        let empGrade = (selectedEmpOption.length && $('#edit_employee_id').val()) 
            ? (selectedEmpOption.data('grade') || '') 
            : window.authGrade;

        let maxCeiling = null;
        let ceilingGradeLabel = null;

        if (Array.isArray(rawCeilings) && rawCeilings.length > 0) {
            if (empGrade) {
                for (let i = 0; i < rawCeilings.length; i++) {
                    let c = rawCeilings[i];
                    if (c && c.grade && c.amount) {
                        let cG = String(c.grade).trim().toLowerCase().replace('grade ', '');
                        let eG = String(empGrade).trim().toLowerCase().replace('grade ', '');
                        if (cG === eG) {
                            maxCeiling = parseFloat(c.amount);
                            ceilingGradeLabel = c.grade;
                            break;
                        }
                    }
                }
            }

            if (maxCeiling === null) {
                for (let i = 0; i < rawCeilings.length; i++) {
                    let a = parseFloat(rawCeilings[i].amount);
                    if (!isNaN(a) && (maxCeiling === null || a > maxCeiling)) {
                        maxCeiling = a;
                    }
                }
            }
        }

        if (maxCeiling !== null && maxCeiling > 0) {
            let gradeInfoStr = ceilingGradeLabel ? (' for ' + ceilingGradeLabel) : (empGrade ? (' for ' + empGrade) : '');
            $('#edit_amountLimitInfo').html('<i class="im im-icon-Information me-1"></i> Max Loan Ceiling' + gradeInfoStr + ': <strong>৳ ' + maxCeiling.toLocaleString() + '</strong>').show();

            if (amt > maxCeiling) {
                $('#edit_amount').addClass('is-invalid');
                $('#edit_amountWarning').html('⚠️ Requested amount (৳ ' + amt.toLocaleString() + ') exceeds maximum ceiling limit of ৳ ' + maxCeiling.toLocaleString() + gradeInfoStr + '!');
            }
        } else {
            $('#edit_amountLimitInfo').hide().html('');
        }
    }

    $('#modal_loan_type_id, #modal_employee_id, #modal_amount, #modal_installments, #modal_required_month, #modal_effective_month').on('input change select2:select', function() {
        validateModalCategoryLimits();

        let amt = parseFloat($('#modal_amount').val()) || 0;
        let months = parseInt($('#modal_installments').val()) || 0;
        let rate = parseFloat($('#modal_loan_type_id option:selected').data('rate')) || 5;

        if (amt > 0 && months > 0) {
            let totalInterest = (amt * (rate / 100)) * (months / 12);
            let totalPayable = amt + totalInterest;
            let emi = totalPayable / months;

            $('#modal_previewPrincipal').text('৳ ' + amt.toLocaleString());
            $('#modal_previewInterest').text('৳ ' + totalInterest.toFixed(2));
            $('#modal_previewEMI').text('৳ ' + emi.toFixed(2));
            $('#modal_emiPreview').show();
        } else {
            $('#modal_emiPreview').hide();
        }
    });

    $('#edit_loan_type_id, #edit_employee_id, #edit_amount, #edit_installments, #edit_required_month, #edit_effective_month').on('input change select2:select', function() {
        validateEditModalCategoryLimits();

        let amt = parseFloat($('#edit_amount').val()) || 0;
        let months = parseInt($('#edit_installments').val()) || 0;
        let rate = parseFloat($('#edit_loan_type_id option:selected').data('rate')) || 5;

        if (amt > 0 && months > 0) {
            let totalInterest = (amt * (rate / 100)) * (months / 12);
            let totalPayable = amt + totalInterest;
            let emi = totalPayable / months;

            $('#edit_previewPrincipal').text('৳ ' + amt.toLocaleString());
            $('#edit_previewInterest').text('৳ ' + totalInterest.toFixed(2));
            $('#edit_previewEMI').text('৳ ' + emi.toFixed(2));
            $('#edit_emiPreview').show();
        } else {
            $('#edit_emiPreview').hide();
        }
    });
</script>
@endpush
@endsection
