<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white text-center py-3">
        <h5 class="mb-1 text-white fw-bold"><i class="im im-icon-Calendar me-2"></i> Employee Leave Type Assignments</h5>
        <small class="text-white-50 d-block">Gender: <strong class="text-white">{{ $users->gender ? ucfirst($users->gender) : 'Not Specified' }}</strong></small>
    </div>
    <div class="card-body">
        <form action="{{ route('users.saveLeaveAssignments', $users->id) }}" method="POST" id="leave-assignment-form">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;" class="text-center">Assign</th>
                            <th>Leave Type Name</th>
                            <th>Gender Criteria</th>
                            <th>Default Days / Year</th>
                            <th style="width: 220px;">Assigned Days / Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveTypes as $type)
                            @php
                                $isAssigned = in_array($type->id, $assignedLeaveTypeIds);
                                $assignedDaysValue = isset($userLeaveAssignments[$type->id]) && $userLeaveAssignments[$type->id] !== null ? $userLeaveAssignments[$type->id] : $type->total_days_per_year;
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input leave-type-checkbox" type="checkbox" name="leave_types[{{ $type->id }}]" value="1" id="leave_type_{{ $type->id }}" {{ $isAssigned ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <label class="form-check-label fw-bold cursor-pointer" for="leave_type_{{ $type->id }}">
                                        {{ $type->name }}
                                    </label>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $type->gender_criteria ?? 'All' }}
                                    </span>
                                </td>
                                <td>{{ $type->total_days_per_year }} days</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="allowed_days[{{ $type->id }}]" class="form-control allowed-days-input" value="{{ $assignedDaysValue }}" min="0" max="365" {{ $isAssigned ? '' : 'disabled' }}>
                                        <span class="input-group-text">days</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No leave types defined in the system. <a href="{{ route('leaveTypes.index') }}">Create Leave Types</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="text-muted small">
                    <i class="im im-icon-Information me-1"></i> Checked leave types will be available for this employee when applying for leave.
                </span>
                <button type="submit" class="btn btn-primary">
                    <i class="im im-icon-Disk me-1"></i> Save Leave Assignments
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.leave-type-checkbox');
        checkboxes.forEach(function (chk) {
            chk.addEventListener('change', function () {
                const tr = this.closest('tr');
                const daysInput = tr.querySelector('.allowed-days-input');
                if (daysInput) {
                    daysInput.disabled = !this.checked;
                }
            });
        });
    });
</script>
