@extends('layouts.default')

@section('title', 'Modify Leave Application')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="im im-icon-Edit me-2"></i>Modify / Edit Leave Application</h5>
                </div>
                <div class="card-body p-4">
                    @if(!empty($canManageLeaves))
                        {{-- Employee Detail Header --}}
                        <div class="alert alert-light border mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted d-block small">Employee</span>
                                <strong class="fs-6 text-dark">{{ $leaveApplication->user->name ?? 'N/A' }} {{ $leaveApplication->user->last_name ?? '' }}</strong>
                                <span class="badge bg-secondary ms-2">{{ $leaveApplication->user->emp_id ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-muted d-block small text-end">Current Status</span>
                                <span class="badge {{ $leaveApplication->status == 'Approved' ? 'bg-success' : ($leaveApplication->status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ $leaveApplication->status }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('leaveApplications.update', $leaveApplication->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Leave Type <span class="text-danger">*</span></label>
                                <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                    @foreach($leaveTypes as $id => $name)
                                        <option value="{{ $id }}" {{ old('leave_type_id', $leaveApplication->leave_type_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', \Carbon\Carbon::parse($leaveApplication->start_date)->format('Y-m-d')) }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', \Carbon\Carbon::parse($leaveApplication->end_date)->format('Y-m-d')) }}" required>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary">Approved Days</label>
                                <input type="number" step="0.5" min="0.5" max="365" name="requested_days" class="form-control border-primary fw-bold" value="{{ old('requested_days', $leaveApplication->requested_days) }}">
                                <small class="text-muted">Override days</small>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_half_day" id="is_half_day" value="1" {{ old('is_half_day', $leaveApplication->is_half_day) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_half_day">
                                Half Day Application (0.5 Day)
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason', $leaveApplication->reason) }}</textarea>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if($canManageLeaves)
                            <div class="mb-4">
                                <label class="form-label fw-bold text-danger">Update Status (HR / Admin Control)</label>
                                <select name="status" class="form-select border-danger fw-bold">
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $leaveApplication->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('leaveApplications.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="im im-icon-Save me-1"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection