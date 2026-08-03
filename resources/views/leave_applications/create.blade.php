@extends('layouts.default')

@section('title', 'Apply for Leave')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="im im-icon-Plus me-2"></i>Apply for Leave</h5>
                </div>
                <div class="card-body p-4">
                    {{-- Leave Balances Box --}}
                    @if(!empty($leaveBalances))
                        <div class="alert alert-light border mb-4">
                            <h6 class="fw-bold mb-2 text-dark"><i class="im im-icon-Information me-1 text-primary"></i> Current Leave Quota Overview</h6>
                            <div class="row g-2">
                                @foreach($leaveBalances as $b)
                                    <div class="col-md-3 col-6">
                                        <div class="bg-white p-2 rounded border text-center">
                                            <small class="text-muted d-block">{{ $b['name'] }}</small>
                                            <strong class="text-primary font-monospace">{{ $b['remaining'] }} / {{ $b['total'] }} Days Left</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('leaveApplications.store') }}" method="POST">
                        @csrf

                        @if($canManageLeaves && !empty($employees))
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Employee <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Choose Employee --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} {{ $emp->last_name }} ({{ $emp->emp_id ?: 'ID: ' . $emp->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endif

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Leave Type <span class="text-danger">*</span></label>
                                <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                    <option value="">-- Select Leave Type --</option>
                                    @foreach($leaveTypes as $id => $name)
                                        <option value="{{ $id }}" {{ old('leave_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y-m-d')) }}" required>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_half_day" id="is_half_day" value="1" {{ old('is_half_day') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_half_day">
                                Apply as Half Day (0.5 Day)
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Reason for Leave <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="Specify detailed reason for requesting leave..." required>{{ old('reason') }}</textarea>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if($canManageLeaves)
                            <div class="mb-4">
                                <label class="form-label fw-bold">Set Initial Status</label>
                                <select name="status" class="form-select">
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved Immediately</option>
                                </select>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('leaveApplications.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="im im-icon-Paper-Plane me-1"></i> Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection