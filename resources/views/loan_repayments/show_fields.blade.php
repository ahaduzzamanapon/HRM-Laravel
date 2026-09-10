@php
    $loan = $loanRepayment->loan;
    $empName = optional(optional($loan)->employee)->name ? (optional($loan->employee)->name . ' ' . (optional($loan->employee)->last_name ?? '')) : 'N/A';
    $appNo = optional($loan)->application_no ?? ('LN-' . ($loanRepayment->loan_id ?? 'N/A'));
    $branchName = optional(optional(optional($loan)->employee)->branch)->branch_name ?? 'N/A';
    $typeName = optional(optional($loan)->loanType)->name ?? 'Staff Loan';
@endphp

<tr>
    <th scope="row" style="width: 250px;">Transaction ID:</th>
    <td><span class="badge bg-secondary font-monospace fs-6"># REP-{{ $loanRepayment->id }}</span></td>
</tr>

<tr>
    <th scope="row">Loan Application ID:</th>
    <td>
        <span class="badge bg-light text-dark font-monospace border fs-6 me-2">{{ $appNo }}</span>
        @if($loanRepayment->loan_id)
            <a href="{{ route('employeeLoans.show', $loanRepayment->loan_id) }}" class="btn btn-sm btn-outline-primary ms-1">
                <i class="fa fa-history me-1"></i> View Loan Details & Full History →
            </a>
        @endif
    </td>
</tr>

<tr>
    <th scope="row">Employee Name & Branch:</th>
    <td>
        <strong class="text-dark">{{ $empName }}</strong>
        <small class="text-muted d-block"><i class="fa fa-building-o me-1"></i>{{ $branchName }}</small>
    </td>
</tr>

<tr>
    <th scope="row">Loan Category / Type:</th>
    <td><span class="badge bg-info text-dark">{{ $typeName }}</span></td>
</tr>

<tr>
    <th scope="row">Repayment Amount:</th>
    <td><span class="badge bg-success fs-5 fw-bold p-2">৳ {{ number_format($loanRepayment->amount, 2) }}</span></td>
</tr>

<tr>
    <th scope="row">Repayment Date:</th>
    <td><span class="fw-semibold text-dark">{{ $loanRepayment->repayment_date ? \Carbon\Carbon::parse($loanRepayment->repayment_date)->format('d M Y, h:i:s A') : '—' }}</span></td>
</tr>

<tr>
    <th scope="row">Payroll Month:</th>
    <td><span class="badge bg-primary">{{ $loanRepayment->payroll_month ? \Carbon\Carbon::parse($loanRepayment->payroll_month)->format('M Y') : '—' }}</span></td>
</tr>

<tr>
    <th scope="row">Remarks / Notes:</th>
    <td>{{ $loanRepayment->remarks ?? 'N/A' }}</td>
</tr>

<tr>
    <th scope="row">Recorded At:</th>
    <td><small class="text-muted">{{ $loanRepayment->created_at ? $loanRepayment->created_at->format('d M Y, h:i A') : '—' }}</small></td>
</tr>
