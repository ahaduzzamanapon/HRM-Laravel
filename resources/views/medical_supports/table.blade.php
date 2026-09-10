<div class="table-responsive">
    <table class="table table-hover align-middle" id="medical-supports-table">
        <thead class="table-light">
            <tr>
                <th>SL</th>
                <th>Employee</th>
                <th>Amount (৳)</th>
                <th>Support Date</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($medicalSupports as $key => $medicalSupport)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <span class="fw-bold text-dark d-block">{{ $medicalSupport->employee->name ?? 'N/A' }} {{ $medicalSupport->employee->last_name ?? '' }}</span>
                    <small class="text-muted">ID: {{ $medicalSupport->employee->emp_id ?? 'N/A' }}</small>
                </td>
                <td class="fw-bold text-dark">৳ {{ number_format($medicalSupport->amount, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($medicalSupport->support_date)->format('d M Y') }}</td>
                <td>
                    @if($medicalSupport->attachment)
                        <a href="{{ asset($medicalSupport->attachment) }}" target="_blank" class="btn btn-xs btn-outline-info rounded-pill px-2">
                            <i class="im im-icon-File-TXT me-1"></i> View Document
                        </a>
                    @else
                        <span class="text-muted small">None</span>
                    @endif
                </td>
                <td>
                    @if($medicalSupport->status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($medicalSupport->status == 'Disbursed')
                        <span class="badge bg-primary">Disbursed</span>
                    @elseif($medicalSupport->status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </td>
                <td>{{ Str::limit($medicalSupport->remarks, 30) }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        @include('layouts.partials.action_buttons', [
                            'viewRoute' => route('medicalSupports.show', [$medicalSupport->id]),
                            'editRoute' => route('medicalSupports.edit', [$medicalSupport->id]),
                            'deleteRoute' => route('medicalSupports.destroy', [$medicalSupport->id]),
                        ])
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">No medical support applications found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
