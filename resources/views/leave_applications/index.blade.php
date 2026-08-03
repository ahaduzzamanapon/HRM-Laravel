@extends('layouts.default')

@section('title', 'Leave Management')

@section('content')
<style>
    .leave-card {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05) !important;
    }
    .leave-card-title {
        color: #212529 !important;
        font-weight: 700 !important;
        font-size: 15px !important;
    }
    .leave-text-sub {
        color: #495057 !important;
        font-size: 13px !important;
    }
    .leave-stat-title {
        color: #6c757d !important;
        font-size: 13px !important;
        font-weight: 600 !important;
    }
    /* Allow dropdown to overflow the table container */
    .leave-card .table-responsive {
        overflow: visible !important;
    }
    .leave-card .card-body {
        overflow: visible !important;
    }
</style>
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0" style="color: #212529;">Leave Management System</h3>
            <p class="mb-0" style="color: #6c757d;">Track, apply for, and manage leave applications branch-wise</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyLeaveModal" data-toggle="modal" data-target="#applyLeaveModal">
                <i class="im im-icon-Plus me-1"></i> Apply for Leave
            </button>
            @if(can('manage_leave_types'))
                <a href="{{ route('leaveTypes.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="im im-icon-Gear me-1"></i> Leave Types
                </a>
            @endif
        </div>
    </div>

    {{-- Employee Leave Balance Cards & Stats Cards (Shown ONLY for Employee view) --}}
    @if(!$canManageLeaves)
        @if(!empty($userLeaveBalances))
            <div class="row g-2 mb-3">
                @foreach($userLeaveBalances as $bal)
                    <div class="col-md-3 col-6 mb-2">
                        <div class="card leave-card h-100">
                            <div class="card-body p-2 px-3 d-flex flex-column justify-content-center h-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="leave-card-title">{{ $bal['name'] }}</span>
                                    <span class="badge bg-secondary text-white font-monospace px-2 py-1">{{ $bal['remaining'] }} / {{ $bal['total'] }} Left</span>
                                </div>
                                <div class="progress mb-1" style="height: 6px; background-color: #e9ecef;">
                                    @php
                                        $percent = $bal['total'] > 0 ? ($bal['used'] / $bal['total']) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar {{ $percent > 80 ? 'bg-danger' : ($percent > 50 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $percent }}%;"></div>
                                </div>
                                <div class="d-flex justify-content-between leave-text-sub">
                                    <span>Used: <strong style="color: #212529;">{{ $bal['used'] }} days</strong></span>
                                    <span>Total: <strong style="color: #212529;">{{ $bal['total'] }} days</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Stats Cards (Total Applications, Pending Approval, Approved, Rejected) --}}
        <div class="row g-2 mb-3">
            <div class="col-md-3 col-6 mb-2">
                <div class="card leave-card text-center py-2 px-3">
                    <div class="fs-3 fw-bold d-flex align-items-center justify-content-center" style="color: #0d6efd;">
                        <i class="im im-icon-File me-2 fs-4"></i>{{ $totalCount }}
                    </div>
                    <div class="leave-stat-title">Total Applications</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card leave-card text-center py-2 px-3">
                    <div class="fs-3 fw-bold d-flex align-items-center justify-content-center" style="color: #ff9800;">
                        <i class="im im-icon-Clock me-2 fs-4"></i>{{ $pendingCount }}
                    </div>
                    <div class="leave-stat-title">Pending Approval</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card leave-card text-center py-2 px-3">
                    <div class="fs-3 fw-bold d-flex align-items-center justify-content-center" style="color: #198754;">
                        <i class="im im-icon-Yes me-2 fs-4"></i>{{ $approvedCount }}
                    </div>
                    <div class="leave-stat-title">Approved</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="card leave-card text-center py-2 px-3">
                    <div class="fs-3 fw-bold d-flex align-items-center justify-content-center" style="color: #dc3545;">
                        <i class="im im-icon-Close me-2 fs-4"></i>{{ $rejectedCount }}
                    </div>
                    <div class="leave-stat-title">Rejected</div>
                </div>
            </div>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="card mb-4 leave-card">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('leaveApplications.index') }}" class="row g-3 align-items-center">
                @if($canManageLeaves && isSuperAdmin())
                    <div class="col-md-3">
                        <select name="branch_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- All Branches --</option>
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <select name="leave_type_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Leave Types --</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Statuses --</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="First Level Approved" {{ request('status') == 'First Level Approved' ? 'selected' : '' }}>First Level Approved</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                @if($canManageLeaves)
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search Employee / ID...">
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Applications List Table --}}
    <div class="card leave-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            @if($canManageLeaves)
                                <th class="ps-3">Employee</th>
                                <th>Branch</th>
                            @endif
                            <th class="{{ !$canManageLeaves ? 'ps-3' : '' }}">Leave Type</th>
                            <th>Date Period</th>
                            <th class="text-center">Days</th>
                            <th class="text-center">Status</th>
                            <th>Approver</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveApplications as $application)
                            <tr>
                                @if($canManageLeaves)
                                    <td class="ps-3">
                                        <strong class="d-block text-dark">{{ $application->user->name ?? 'N/A' }} {{ $application->user->last_name ?? '' }}</strong>
                                        <small style="color: #6c757d;">{{ $application->user->emp_id ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ optional(optional($application->user)->branch)->branch_name ?? 'Default Branch' }}
                                        </span>
                                    </td>
                                @endif
                                <td class="{{ !$canManageLeaves ? 'ps-3' : '' }}">
                                    <span class="badge bg-primary">
                                        {{ $application->leaveType->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block fw-bold text-dark">{{ \Carbon\Carbon::parse($application->start_date)->format('d M, Y') }}</span>
                                    <small style="color: #6c757d;">to {{ \Carbon\Carbon::parse($application->end_date)->format('d M, Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-6 font-monospace" style="color: #212529;">{{ $application->requested_days }}</span>
                                    @if($application->is_half_day)
                                        <small class="d-block text-warning font-monospace">(Half Day)</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($application->status === 'Approved')
                                        <span class="badge bg-success"><i class="im im-icon-Yes"></i> Approved</span>
                                    @elseif($application->status === 'First Level Approved')
                                        <span class="badge bg-info text-dark"><i class="im im-icon-Check"></i> First Level Approved</span>
                                    @elseif($application->status === 'Pending')
                                        <span class="badge bg-warning text-dark"><i class="im im-icon-Clock-Forward"></i> Pending</span>
                                    @else
                                        <span class="badge bg-danger"><i class="im im-icon-Close"></i> Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->status == 'First Level Approved')
                                        <small class="d-block" style="color: #0d6efd;">{{ $application->finalApprover->name ?? 'Pending Admin' }}</small>
                                    @elseif($application->status == 'Approved')
                                        <small class="d-block text-success">{{ $application->approver->name ?? 'Approved' }}</small>
                                    @else
                                        <small class="d-block" style="color: #6c757d;">{{ $application->approver->name ?? 'N/A' }}</small>
                                    @endif
                                </td>
                                <td class="text-end pe-3" style="white-space: nowrap;">
                                    @if($canManageLeaves)
                                        {{-- Admin/HR: inline buttons matching standard format --}}
                                        <div class="action-buttons-group">
                                            <button type="button" onclick="openLeaveViewModal({{ $application->id }})" class="btn-action btn-action-view" title="View Details" data-bs-toggle="tooltip">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button type="button" onclick="openLeaveEditModal({{ $application->id }})" class="btn-action btn-action-edit" title="Edit / Modify" data-bs-toggle="tooltip">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            
                                            @if(isSuperAdmin() || can('approve_leave'))
                                                @if(in_array($application->status, ['Pending', 'First Level Approved']))
                                                    <form action="{{ route('leaveApplications.final.approve', $application->id) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Approve this leave?')">
                                                        @csrf
                                                        <button type="submit" class="btn-action btn-action-approve" title="Approve Leave" data-bs-toggle="tooltip">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(!in_array($application->status, ['Approved', 'Rejected']))
                                                    <form action="{{ route('leaveApplications.reject', $application->id) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Reject this leave?')">
                                                        @csrf
                                                        <button type="submit" class="btn-action btn-action-reject" title="Reject Leave" data-bs-toggle="tooltip">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            
                                            <form action="{{ route('leaveApplications.destroy', $application->id) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Delete this leave record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-action-delete" title="Delete Record" data-bs-toggle="tooltip">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        {{-- Employee: inline buttons matching standard format --}}
                                        <div class="action-buttons-group">
                                            <button type="button" onclick="openLeaveViewModal({{ $application->id }})" class="btn-action btn-action-view" title="View Details" data-bs-toggle="tooltip">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            @if($application->status === 'Pending')
                                                <button type="button" onclick="openLeaveEditModal({{ $application->id }})" class="btn-action btn-action-edit" title="Edit Application" data-bs-toggle="tooltip">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <form action="{{ route('leaveApplications.destroy', $application->id) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Cancel this leave application?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-action-reject" title="Cancel Application" data-bs-toggle="tooltip">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5" style="color: #6c757d;">
                                    <i class="im im-icon-Information display-6 d-block mb-2 text-secondary"></i>
                                    No leave applications found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($leaveApplications->hasPages())
            <div class="card-footer bg-transparent border-0 py-3">
                {{ $leaveApplications->links() }}
            </div>
        @endif
    </div>
</div>

{{-- 1. Apply For Leave Modal --}}
<div class="modal fade" id="applyLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3" style="background: #eaf4eb; border-bottom: 2px solid #b2dfdb;">
                <h5 class="modal-title fw-bold" style="color: #1a6040;"><i class="im im-icon-Plus me-2"></i>Apply for Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('leaveApplications.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if($canManageLeaves && !empty($employees))
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Employee <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Choose Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->name }} {{ $emp->last_name }} ({{ $emp->emp_id ?: 'ID: ' . $emp->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Leave Type <span class="text-danger">*</span></label>
                            <select name="leave_type_id" class="form-select" required>
                                <option value="">-- Select Leave Type --</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_half_day" id="modal_is_half_day" value="1">
                        <label class="form-check-label fw-bold" for="modal_is_half_day">
                            Apply as Half Day (0.5 Day)
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Leave <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Specify detailed reason for requesting leave..." required></textarea>
                    </div>

                    @if($canManageLeaves)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Set Initial Status</label>
                            <select name="status" class="form-select">
                                <option value="Pending">Pending Approval</option>
                                <option value="Approved">Approved Immediately</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="im im-icon-Paper-Plane me-1"></i> Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. View Leave Details Modal --}}
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3" style="background: #e8f4f8; border-bottom: 2px solid #b8dce8;">
                <h5 class="modal-title fw-bold" style="color: #1a6080;"><i class="im im-icon-File-Chart me-2"></i>Leave Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-md-6">
                        <span class="text-muted d-block small">Employee Name</span>
                        <h5 class="fw-bold mb-0 text-dark" id="view_employee_name">Loading...</h5>
                        <span class="badge bg-secondary" id="view_emp_id"></span>
                        <span class="badge bg-light text-dark border" id="view_branch_name"></span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="text-muted d-block small">Current Status</span>
                        <span id="view_status"></span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Leave Type</small>
                            <strong class="fs-6 text-primary" id="view_leave_type"></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Period</small>
                            <strong class="fs-6" id="view_period" style="white-space: nowrap; font-size: 13px !important;"></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Duration Requested</small>
                            <strong class="fs-6 text-dark" id="view_days"></strong>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Reason for Leave</h6>
                    <div class="bg-light p-3 rounded border text-muted" id="view_reason"></div>
                </div>

                <div class="border-top pt-3" id="view_approval_section">
                    <h6 class="fw-bold mb-3">Approved By</h6>
                    <div class="row text-center g-2" id="view_approvers_row">
                        <div class="col-md-6" id="view_approver_1_col" style="display:none;">
                            <div class="p-2 rounded" style="background:#e8f5e9; border: 1px solid #a5d6a7;">
                                <small class="text-muted d-block">HR Approver</small>
                                <strong class="text-success" id="view_approver_1"></strong>
                            </div>
                        </div>
                        <div class="col-md-6" id="view_approver_2_col" style="display:none;">
                            <div class="p-2 rounded" style="background:#e3f2fd; border: 1px solid #90caf9;">
                                <small class="text-muted d-block">Final Approver</small>
                                <strong class="text-primary" id="view_approver_2"></strong>
                            </div>
                        </div>
                        <div class="col-12" id="view_no_approver" style="display:none;">
                            <span class="text-muted fst-italic"><i class="im im-icon-Clock-Forward me-1"></i>No approvals taken yet</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- 3. Edit / Modify Leave Modal --}}
<div class="modal fade" id="editLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3" style="background: #fff8e1; border-bottom: 2px solid #ffe082;">
                <h5 class="modal-title fw-bold" style="color: #7a5800;"><i class="im im-icon-Edit me-2"></i>Modify / Edit Leave Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLeaveForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    @if($canManageLeaves)
                        <div class="alert alert-light border mb-4">
                            <span class="text-muted d-block small">Employee</span>
                            <strong class="fs-6 text-dark" id="edit_employee_name">Loading...</strong>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Leave Type <span class="text-danger">*</span></label>
                            <select name="leave_type_id" id="edit_leave_type_id" class="form-select" required>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold text-primary">Approved Days</label>
                            <input type="number" step="0.5" min="0.5" max="365" name="requested_days" id="edit_requested_days" class="form-control border-primary fw-bold">
                            <small class="text-muted">Override days</small>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_half_day" id="edit_is_half_day" value="1">
                        <label class="form-check-label fw-bold" for="edit_is_half_day">
                            Half Day Application (0.5 Day)
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="edit_reason" class="form-control" rows="3" required></textarea>
                    </div>

                    @if($canManageLeaves)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Update Status (HR / Admin Control)</label>
                            <select name="status" id="edit_status" class="form-select border-danger fw-bold">
                                <option value="Pending">Pending</option>
                                <option value="First Level Approved">First Level Approved</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="im im-icon-Save me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openLeaveViewModal(id) {
        $('#view_employee_name').text('Loading...');
        $('#viewLeaveModal').modal('show');
        $.ajax({
            url: '/leaveApplications/' + id,
            type: 'GET',
            dataType: 'json',
            headers: { 'Accept': 'application/json' },
            success: function(res) {
                if(res.success) {
                    let d = res.data;
                    $('#view_employee_name').text(d.user_name);
                    $('#view_emp_id').text(d.emp_id);
                    $('#view_branch_name').text(d.branch_name);

                    let badgeClass = 'badge fs-6 ';
                    if (d.status === 'Approved') badgeClass += 'bg-success';
                    else if (d.status === 'Rejected') badgeClass += 'bg-danger';
                    else badgeClass += 'bg-warning text-dark';

                    $('#view_status').text(d.status).attr('class', badgeClass);
                    $('#view_leave_type').text(d.leave_type_name);
                    $('#view_period').text(d.start_date_formatted + ' to ' + d.end_date_formatted);
                    $('#view_days').text(d.requested_days + ' Days ' + (d.is_half_day ? '(Half Day)' : ''));
                    $('#view_reason').text(d.reason || 'No reason provided.');

                    // Smart approver display: only show who actually took action
                    let hasAny = false;
                    let a1 = d.approver_name && d.approver_name !== 'N/A' && d.approver_name !== 'Pending';
                    let a2 = d.final_approver_name && d.final_approver_name !== 'N/A' && d.final_approver_name !== 'Pending Admin';

                    if (a1) {
                        $('#view_approver_1').text(d.approver_name);
                        $('#view_approver_1_col').show();
                        hasAny = true;
                    } else {
                        $('#view_approver_1_col').hide();
                    }

                    if (a2) {
                        $('#view_approver_2').text(d.final_approver_name);
                        $('#view_approver_2_col').show();
                        hasAny = true;
                    } else {
                        $('#view_approver_2_col').hide();
                    }

                    $('#view_no_approver').toggle(!hasAny);
                    // Hide entire section if pending
                    $('#view_approval_section').toggle(d.status !== 'Pending' || hasAny);
                }
            },
            error: function() {
                alert('Could not fetch leave application details.');
            }
        });
    }

    function openLeaveEditModal(id) {
        $('#edit_employee_name').text('Loading...');
        $('#editLeaveModal').modal('show');
        $.ajax({
            url: '/leaveApplications/' + id + '/edit',
            type: 'GET',
            dataType: 'json',
            headers: { 'Accept': 'application/json' },
            success: function(res) {
                if(res.success) {
                    let d = res.data;
                    $('#editLeaveForm').attr('action', '/leaveApplications/' + d.id);
                    $('#edit_employee_name').text(d.user_name + ' (' + d.emp_id + ')');
                    $('#edit_start_date').val(d.start_date);
                    $('#edit_end_date').val(d.end_date);
                    $('#edit_requested_days').val(d.requested_days);
                    $('#edit_reason').val(d.reason);
                    $('#edit_leave_type_id').val(d.leave_type_id);
                    $('#edit_status').val(d.status);
                    $('#edit_is_half_day').prop('checked', d.is_half_day == 1);
                }
            },
            error: function() {
                alert('Could not fetch leave application for editing.');
            }
        });
    }
</script>
@endpush
@endsection
