<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="departmental-cases-table">
        <thead class="table-light">
            <tr>
                <th class="ps-3">Case Ref / Date</th>
                <th>Employee Details</th>
                <th>Allegation Info</th>
                <th>Status</th>
                <th>Penalty & Amount</th>
                <th class="text-center">Notified</th>
                <th class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($departmentalCases as $case)
            <tr>
                <td class="ps-3 fw-bold text-nowrap">
                    <span class="d-block text-dark">{{ $case->case_no ?? ('DC-' . str_pad($case->id, 4, '0', STR_PAD_LEFT)) }}</span>
                    <small class="text-muted font-monospace">
                        <i class="fa fa-calendar-o me-1"></i>
                        {{ $case->incident_date ? \Carbon\Carbon::parse($case->incident_date)->format('d M, Y') : $case->created_at->format('d M, Y') }}
                    </small>
                </td>

                <td>
                    @if($case->employee)
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 font-weight-bold" style="width: 38px; height: 38px; font-size: 14px;">
                                {{ strtoupper(substr($case->employee->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $case->employee->name }} {{ $case->employee->last_name }}</span>
                                <small class="text-muted">ID: {{ $case->employee->emp_id ?? 'N/A' }} | {{ $case->employee->branch->branch_name ?? 'Head Office' }}</small>
                            </div>
                        </div>
                    @else
                        <span class="text-danger">Employee Removed</span>
                    @endif
                </td>

                <td>
                    <span class="fw-semibold text-dark d-block">{{ $case->allegation_type }}</span>
                    <span class="badge bg-light text-dark border">{{ $case->allegation_category }}</span>
                </td>

                <td>
                    <span class="badge {{ $case->status_badge_class }} px-2 py-1">
                        {{ $case->status ?? 'Pending' }}
                    </span>
                    @if($case->show_cause_date)
                        <small class="d-block text-muted mt-1" title="Show Cause Notice Date">
                            <i class="fa fa-clock-o me-1"></i>SC: {{ \Carbon\Carbon::parse($case->show_cause_date)->format('d M') }}
                        </small>
                    @endif
                </td>

                <td>
                    @if($case->penalty)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 d-inline-block mb-1">
                            <i class="fa fa-gavel me-1"></i>{{ $case->penalty->name }}
                        </span>
                        @if($case->penalty_amount && $case->penalty_amount > 0)
                            <div class="fw-bold text-danger small">
                                Amount: {{ number_format($case->penalty_amount, 2) }}
                            </div>
                        @endif
                    @else
                        <span class="text-muted small">No Penalty</span>
                    @endif
                </td>

                <td class="text-center">
                    @if($case->notified_at)
                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1" title="Notified on {{ $case->notified_at->format('Y-m-d H:i') }}">
                            <i class="fa fa-check-circle me-1"></i>Notified
                        </span>
                        <small class="d-block text-muted" style="font-size: 11px;">{{ $case->notified_at->format('d M, H:i') }}</small>
                    @else
                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 px-2 py-1">
                            <i class="fa fa-clock-o me-1"></i>Not Sent
                        </span>
                    @endif
                </td>

                <td class="text-end pe-3 text-nowrap">
                    <div class="btn-group btn-group-sm">
                        <!-- Show Details -->
                        @if($isEmployeeOnly || can('view_departmental_cases') || can('view_my_departmental_cases') || can('manage_departmental_cases'))
                        <a href="{{ route('departmentalCases.show', [$case->id]) }}" class="btn btn-outline-info" title="View Case Details">
                            <i class="fa fa-eye"></i>
                        </a>
                        @endif

                        <!-- Edit Case -->
                        @if(!$isEmployeeOnly && (can('edit_departmental_cases') || can('manage_departmental_cases')))
                        <a href="{{ route('departmentalCases.edit', [$case->id]) }}" class="btn btn-outline-primary" title="Edit Case">
                            <i class="fa fa-pencil"></i>
                        </a>
                        @endif

                        <!-- Notify Employee Form Button -->
                        @if(!$isEmployeeOnly && (can('notify_departmental_cases') || can('manage_departmental_cases')))
                        <form action="{{ route('departmentalCases.notify', [$case->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Send official disciplinary notification email to {{ addslashes($case->employee->name ?? 'this employee') }}?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" title="Send Notification to Employee">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                        @endif

                        <!-- Delete Case -->
                        @if(!$isEmployeeOnly && (can('delete_departmental_cases') || can('manage_departmental_cases')))
                        <form action="{{ route('departmentalCases.destroy', [$case->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this disciplinary case?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Delete Case">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fa fa-folder-open-o fa-3x d-block mb-3 text-secondary"></i>
                    <h5>No Disciplinary Cases Found</h5>
                    <p class="mb-0 small">No cases match your filter criteria or no disciplinary cases registered yet.</p>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
