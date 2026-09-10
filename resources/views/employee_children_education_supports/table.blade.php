<div class="table-responsive">
    <table class="table table-hover align-middle" id="employee-children-education-supports-table">
        <thead class="table-light">
            <tr>
                <th>SL</th>
                <th>Employee</th>
                <th>Child Name</th>
                <th>Exam / GPA</th>
                <th>Assistance Amount (৳)</th>
                <th>Support Date</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($employeeChildrenEducationSupports as $key => $support)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <span class="fw-bold text-dark d-block">{{ $support->employee->name ?? 'N/A' }} {{ $support->employee->last_name ?? '' }}</span>
                    <small class="text-muted">ID: {{ $support->employee->emp_id ?? 'N/A' }}</small>
                </td>
                <td class="fw-bold text-dark">{{ $support->child_name ?? 'N/A' }}</td>
                <td>
                    <span class="d-block text-dark">{{ $support->exam_name ?? 'N/A' }}</span>
                    @if($support->gpa)
                        <small class="badge bg-light text-dark">GPA: {{ $support->gpa }}</small>
                    @endif
                </td>
                <td class="fw-bold text-dark">৳ {{ number_format($support->financial_assistance, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($support->support_date)->format('d M Y') }}</td>
                <td>
                    @if($support->attachment)
                        <a href="{{ asset($support->attachment) }}" target="_blank" class="btn btn-xs btn-outline-info rounded-pill px-2">
                            <i class="im im-icon-File-TXT me-1"></i> View Document
                        </a>
                    @else
                        <span class="text-muted small">None</span>
                    @endif
                </td>
                <td>
                    @if($support->status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($support->status == 'Disbursed')
                        <span class="badge bg-primary">Disbursed</span>
                    @elseif($support->status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        @include('layouts.partials.action_buttons', [
                            'viewRoute' => route('employeeChildrenEducationSupports.show', [$support->id]),
                            'editRoute' => route('employeeChildrenEducationSupports.edit', [$support->id]),
                            'deleteRoute' => route('employeeChildrenEducationSupports.destroy', [$support->id]),
                        ])
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">No children education support applications found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
