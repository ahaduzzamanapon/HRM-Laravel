@extends('layouts.default')

@section('title', 'PF Loan Applications')

@php
    $isEmployee = \App\Services\AuthorizationEngine::isEmployeeRole(Auth::user());
@endphp

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">PF Loan Applications</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoanModal"><i class="fa fa-plus me-1"></i> Add New Application</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>PF Account No</th>
                                    <th>Requested Amount</th>
                                    <th>Installments</th>
                                    <th>Approved Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $index => $loan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $loan->employee->name ?? 'N/A' }} {{ $loan->employee->last_name ?? '' }}</td>
                                    <td>{{ $loan->employee->pf_account_number ?? 'N/A' }}</td>
                                    <td>${{ number_format($loan->amount, 2) }}</td>
                                    <td>{{ $loan->installments }}</td>
                                    <td>{{ $loan->approved_amount ? '$' . number_format($loan->approved_amount, 2) : 'N/A' }}</td>
                                    <td>
                                        @if($loan->status == 'Pending')
                                            <span class="badge bg-warning text-dark">{{ $loan->status }}</span>
                                        @elseif($loan->status == 'Approved')
                                            <span class="badge bg-info">{{ $loan->status }}</span>
                                        @elseif($loan->status == 'Disbursed')
                                            <span class="badge bg-success">{{ $loan->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $loan->status }}</span>
                                        @endif
                                    </td>
                                     <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <!-- Details Button (Available to all) -->
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#showModal{{ $loan->id }}">
                                                <i class="fa fa-eye me-1"></i> Details
                                            </button>

                                            <!-- Admin Approve / Disburse Actions -->
                                            @if(!$isEmployee)
                                                @if($loan->status == 'Pending')
                                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $loan->id }}">Approve</button>
                                                @elseif($loan->status == 'Approved')
                                                    <form action="{{ route('pf.loans.disburse', $loan->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary">Disburse</button>
                                                    </form>
                                                @endif
                                            @endif

                                            <!-- Edit Button (Admin always, Employee if Pending & Owner) -->
                                            @if($loan->status == 'Pending' && (!$isEmployee || $loan->employee_id == Auth::id()))
                                                <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $loan->id }}">
                                                    <i class="fa fa-pencil me-1"></i> Edit
                                                </button>
                                            @elseif(!$isEmployee && $loan->status == 'Approved')
                                                <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $loan->id }}">
                                                    <i class="fa fa-pencil me-1"></i> Edit
                                                </button>
                                            @endif

                                            <!-- Delete Button (Admin if not disbursed, Employee if Pending & Owner) -->
                                            @if((!$isEmployee && $loan->status != 'Disbursed') || ($isEmployee && $loan->status == 'Pending' && $loan->employee_id == Auth::id()))
                                                <form action="{{ route('pf.loans.destroy', $loan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete/cancel this PF loan application?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash me-1"></i> Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                     </td>
                                </tr>

                                <!-- Details Modal -->
                                <div class="modal fade" id="showModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title fw-bold"><i class="fa fa-info-circle me-2"></i>PF Loan Details #{{ $loan->id }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="text-muted mb-1 d-block">Employee Name</label>
                                                        <h6 class="fw-bold">{{ $loan->employee->name ?? 'N/A' }} {{ $loan->employee->last_name ?? '' }}</h6>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="text-muted mb-1 d-block">PF Account Number</label>
                                                        <h6 class="fw-bold">{{ $loan->employee->pf_account_number ?? 'N/A' }}</h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="text-muted mb-1 d-block">Requested Amount</label>
                                                        <h5 class="text-primary fw-bold">${{ number_format($loan->amount, 2) }}</h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="text-muted mb-1 d-block">Installments</label>
                                                        <h5 class="fw-bold">{{ $loan->installments }} Months</h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="text-muted mb-1 d-block">Monthly Installment</label>
                                                        <h5 class="text-dark fw-bold">${{ number_format($loan->monthly_installment ?? ($loan->amount / ($loan->installments ?: 1)), 2) }}</h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="text-muted mb-1 d-block">Application Status</label>
                                                        <div>
                                                            @if($loan->status == 'Pending')
                                                                <span class="badge bg-warning text-dark">{{ $loan->status }}</span>
                                                            @elseif($loan->status == 'Approved')
                                                                <span class="badge bg-info">{{ $loan->status }}</span>
                                                            @elseif($loan->status == 'Disbursed')
                                                                <span class="badge bg-success">{{ $loan->status }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $loan->status }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="text-muted mb-1 d-block">Approved Amount</label>
                                                        <h5 class="text-success fw-bold">{{ $loan->approved_amount ? '$' . number_format($loan->approved_amount, 2) : 'Not Approved Yet' }}</h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="text-muted mb-1 d-block">Outstanding Balance</label>
                                                        <h5 class="text-danger fw-bold">${{ number_format($loan->outstanding_balance ?? 0, 2) }}</h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="text-muted mb-1 d-block">Application Date</label>
                                                        <p class="mb-0 fw-bold">{{ $loan->created_at ? $loan->created_at->format('M d, Y h:i A') : 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="text-muted mb-1 d-block">Disbursement Date</label>
                                                        <p class="mb-0 fw-bold">{{ $loan->disbursement_date ? \Carbon\Carbon::parse($loan->disbursement_date)->format('M d, Y') : 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="text-muted mb-1 d-block">Remarks / Reason</label>
                                                        <div class="p-3 bg-light rounded">{{ $loan->remarks ?: 'No remarks provided.' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Approve Modal -->
                                @if(!$isEmployee)
                                <div class="modal fade" id="approveModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('pf.loans.approve', $loan->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Loan Application</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Requested Amount</label>
                                                        <input type="number" class="form-control" value="{{ $loan->amount }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Installments</label>
                                                        <input type="number" class="form-control" value="{{ $loan->installments }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Approved Amount <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control" name="approved_amount" value="{{ $loan->amount }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Edit Modal -->
                                @if(!$isEmployee || ($loan->employee_id == Auth::id() && $loan->status == 'Pending'))
                                <div class="modal fade" id="editModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('pf.loans.update', $loan->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-warning text-white">
                                                    <h5 class="modal-title fw-bold">Edit PF Loan Application</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Employee</label>
                                                        <input type="text" class="form-control" value="{{ $loan->employee->name ?? 'N/A' }} {{ $loan->employee->last_name ?? '' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Requested Amount <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control" name="amount" value="{{ $loan->amount }}" required min="1000">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Number of Installments <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="installments" value="{{ $loan->installments }}" required min="1">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Remarks</label>
                                                        <textarea class="form-control" name="remarks" rows="3">{{ $loan->remarks }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning text-white"><i class="fa fa-check me-1"></i> Update Application</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Loan Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pf.loans.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-plus-circle me-1"></i> Add Loan Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($isEmployee)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Applicant Employee</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }} {{ Auth::user()->last_name }} (PF Acc: {{ Auth::user()->pf_account_number ?? 'N/A' }})" readonly>
                            <input type="hidden" name="employee_id" value="{{ Auth::id() }}">
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-bold">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->last_name }} ({{ $employee->pf_account_number ?? 'No PF Acc' }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Requested Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount" required min="1000" placeholder="e.g. 5000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Number of Installments <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="installments" required min="1" placeholder="e.g. 12">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Remarks / Purpose <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="remarks" rows="3" required placeholder="State reason for loan application"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane me-1"></i> Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable().destroy();
        }
        $('#datatable').DataTable();
    });
</script>
@endsection
