<tr>
    <th scope="row" style="width: 200px;">Application ID:</th>
    <td>#{{ $employeeChildrenEducationSupport->id }}</td>
</tr>

<tr>
    <th scope="row">Employee Name:</th>
    <td><span class="fw-bold">{{ $employeeChildrenEducationSupport->employee->name ?? 'N/A' }} {{ $employeeChildrenEducationSupport->employee->last_name ?? '' }}</span> (ID: {{ $employeeChildrenEducationSupport->employee->emp_id ?? 'N/A' }})</td>
</tr>

<tr>
    <th scope="row">Child Name:</th>
    <td class="fw-bold text-dark">{{ $employeeChildrenEducationSupport->child_name ?? 'N/A' }}</td>
</tr>

<tr>
    <th scope="row">Exam / Academic Level & GPA:</th>
    <td>{{ $employeeChildrenEducationSupport->exam_name ?? 'N/A' }} @if($employeeChildrenEducationSupport->gpa) (GPA: {{ $employeeChildrenEducationSupport->gpa }}) @endif</td>
</tr>

<tr>
    <th scope="row">Assistance Amount:</th>
    <td class="fw-bold text-success" style="font-size: 1.1rem;">৳ {{ number_format($employeeChildrenEducationSupport->financial_assistance, 2) }}</td>
</tr>

<tr>
    <th scope="row">Support Date:</th>
    <td>{{ \Carbon\Carbon::parse($employeeChildrenEducationSupport->support_date)->format('d M Y') }}</td>
</tr>

<tr>
    <th scope="row">Attachment Document:</th>
    <td>
        @if($employeeChildrenEducationSupport->attachment)
            <a href="{{ asset($employeeChildrenEducationSupport->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
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
        @if($employeeChildrenEducationSupport->status == 'Approved')
            <span class="badge bg-success px-3 py-2">Approved</span>
        @elseif($employeeChildrenEducationSupport->status == 'Disbursed')
            <span class="badge bg-primary px-3 py-2">Disbursed</span>
        @elseif($employeeChildrenEducationSupport->status == 'Rejected')
            <span class="badge bg-danger px-3 py-2">Rejected</span>
        @else
            <span class="badge bg-warning text-dark px-3 py-2">Pending Review</span>
        @endif
    </td>
</tr>

@if($employeeChildrenEducationSupport->approver)
<tr>
    <th scope="row">Approved By:</th>
    <td>{{ $employeeChildrenEducationSupport->approver->name ?? 'N/A' }} ({{ \Carbon\Carbon::parse($employeeChildrenEducationSupport->approved_at)->format('d M Y h:i A') }})</td>
</tr>
@endif

<tr>
    <th scope="row">Remarks / Details:</th>
    <td>{{ $employeeChildrenEducationSupport->remarks ?? 'N/A' }}</td>
</tr>

@if(can('manage_employee_children_education_supports') || isSuperAdmin())
<tr>
    <th scope="row">Update Status:</th>
    <td>
        <form action="{{ route('welfare.updateStatus', ['education', $employeeChildrenEducationSupport->id]) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf
            <select name="status" class="form-select w-auto">
                <option value="Pending" {{ $employeeChildrenEducationSupport->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $employeeChildrenEducationSupport->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Disbursed" {{ $employeeChildrenEducationSupport->status == 'Disbursed' ? 'selected' : '' }}>Disbursed</option>
                <option value="Rejected" {{ $employeeChildrenEducationSupport->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <input type="text" name="admin_remarks" value="{{ $employeeChildrenEducationSupport->admin_remarks }}" placeholder="Admin notes/remarks" class="form-control w-50">
            <button type="submit" class="btn btn-primary rounded-pill px-3">Update Status</button>
        </form>
    </td>
</tr>
@endif
