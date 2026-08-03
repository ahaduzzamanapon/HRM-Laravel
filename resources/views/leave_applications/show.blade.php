@extends('layouts.default')

@section('title', 'Leave Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="im im-icon-File-Chart me-2"></i>Leave Application Details</h5>
                    <a href="{{ route('leaveApplications.index') }}" class="btn btn-sm btn-light">
                        <i class="im im-icon-Arrow-Left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <span class="text-muted d-block small">Employee Name</span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $leaveApplication->user->name ?? 'N/A' }} {{ $leaveApplication->user->last_name ?? '' }}</h5>
                            <span class="badge bg-secondary">{{ $leaveApplication->user->emp_id ?? 'N/A' }}</span>
                            <span class="badge bg-light text-dark border">{{ optional(optional($leaveApplication->user)->branch)->branch_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="text-muted d-block small">Current Status</span>
                            <span class="badge fs-6 {{ $leaveApplication->status == 'Approved' ? 'bg-success' : ($leaveApplication->status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $leaveApplication->status }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block">Leave Type</small>
                                <strong class="fs-6 text-primary">{{ $leaveApplication->leaveType->name ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block">Period</small>
                                <strong class="fs-6">{{ \Carbon\Carbon::parse($leaveApplication->start_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($leaveApplication->end_date)->format('d M, Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block">Duration Requested</small>
                                <strong class="fs-6 text-dark">{{ $leaveApplication->requested_days }} Days {{ $leaveApplication->is_half_day ? '(Half Day)' : '' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Reason for Leave</h6>
                        <div class="bg-light p-3 rounded border text-muted">
                            {{ $leaveApplication->reason ?: 'No reason provided.' }}
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <h6 class="fw-bold mb-3">Approval History</h6>
                        <div class="row text-center g-2">
                            <div class="col-md-6">
                                <div class="p-2 border rounded">
                                    <small class="text-muted d-block">First Level Approver (HR)</small>
                                    <strong>{{ $leaveApplication->approver->name ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded">
                                    <small class="text-muted d-block">Final Approver (Admin)</small>
                                    <strong>{{ $leaveApplication->finalApprover->name ?? ($leaveApplication->approved_by ? 'Admin' : 'N/A') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        @if($canManageLeaves)
                            <a href="{{ route('leaveApplications.edit', $leaveApplication->id) }}" class="btn btn-primary">
                                <i class="im im-icon-Edit me-1"></i> Modify / Edit Leave
                            </a>
                        @endif
                        <a href="{{ route('leaveApplications.index') }}" class="btn btn-secondary">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection