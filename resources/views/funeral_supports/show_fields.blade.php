<tr>
    <th scope="row" style="width: 200px;">Application ID:</th>
    <td>#{{ $funeralSupport->id }}</td>
</tr>

<tr>
    <th scope="row">Employee Name:</th>
    <td><span class="fw-bold">{{ $funeralSupport->employee->name ?? 'N/A' }} {{ $funeralSupport->employee->last_name ?? '' }}</span> (ID: {{ $funeralSupport->employee->emp_id ?? 'N/A' }})</td>
</tr>

<tr>
    <th scope="row">Support Amount:</th>
    <td class="fw-bold text-success" style="font-size: 1.1rem;">৳ {{ number_format($funeralSupport->amount, 2) }}</td>
</tr>

<tr>
    <th scope="row">Support Date:</th>
    <td>{{ \Carbon\Carbon::parse($funeralSupport->support_date)->format('d M Y') }}</td>
</tr>

<tr>
    <th scope="row">Attachment Document:</th>
    <td>
        @if($funeralSupport->attachment)
            <a href="{{ asset($funeralSupport->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                <i class="im im-icon-File-TXT me-1"></i> View / Download Document
            </a>
        @else
            <span class="text-muted">No document attached</span>
        @endif
    </td>
</tr>

<tr>
    <th scope="row">Application Status:</th>
    <td>
        @if($funeralSupport->status == 'Approved')
            <span class="badge bg-success px-3 py-2">Approved</span>
        @elseif($funeralSupport->status == 'Disbursed')
            <span class="badge bg-primary px-3 py-2">Disbursed</span>
        @elseif($funeralSupport->status == 'Rejected')
            <span class="badge bg-danger px-3 py-2">Rejected</span>
        @else
            <span class="badge bg-warning text-dark px-3 py-2">Pending Review</span>
        @endif
    </td>
</tr>

@if($funeralSupport->approver)
<tr>
    <th scope="row">Approved By:</th>
    <td>{{ $funeralSupport->approver->name ?? 'N/A' }} ({{ \Carbon\Carbon::parse($funeralSupport->approved_at)->format('d M Y h:i A') }})</td>
</tr>
@endif

<tr>
    <th scope="row">Remarks / Details:</th>
    <td>{{ $funeralSupport->remarks ?? 'N/A' }}</td>
</tr>

@if(can('manage_funeral_supports') || isSuperAdmin())
<tr>
    <th scope="row">Update Status:</th>
    <td>
        <form action="{{ route('welfare.updateStatus', ['funeral', $funeralSupport->id]) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf
            <select name="status" class="form-select w-auto">
                <option value="Pending" {{ $funeralSupport->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $funeralSupport->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Disbursed" {{ $funeralSupport->status == 'Disbursed' ? 'selected' : '' }}>Disbursed</option>
                <option value="Rejected" {{ $funeralSupport->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <input type="text" name="admin_remarks" value="{{ $funeralSupport->admin_remarks }}" placeholder="Admin notes/remarks" class="form-control w-50">
            <button type="submit" class="btn btn-primary rounded-pill px-3">Update Status</button>
        </form>
    </td>
</tr>
@endif
