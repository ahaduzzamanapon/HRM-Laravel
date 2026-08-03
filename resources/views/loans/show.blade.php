@extends('layouts.default')

@section('content')
<div class="container py-4">
    @include('flash::message')

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0 fw-bold"><i class="im im-icon-Coins me-2 text-success"></i>Loan Details - {{ $loan->application_no ?? 'LN-'.$loan->id }}</h5>
                <small class="text-muted">Applied Date: {{ $loan->created_at->format('d M, Y (h:i A)') }}</small>
            </div>
            <div>
                @if($loan->status === 'Pending')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="im im-icon-Clock me-1"></i> Pending Approval</span>
                @elseif($loan->status === 'Approved')
                    <span class="badge bg-info text-white fs-6 px-3 py-2"><i class="im im-icon-Check me-1"></i> Approved (Awaiting Disbursement)</span>
                @elseif($loan->status === 'Disbursed')
                    <span class="badge bg-success fs-6 px-3 py-2"><i class="im im-icon-Yes me-1"></i> Disbursed & Active</span>
                @else
                    <span class="badge bg-danger fs-6 px-3 py-2"><i class="im im-icon-Close me-1"></i> Rejected</span>
                @endif
            </div>
        </div>
        <div class="card-body p-4">
            {{-- Employee & Loan Header Info --}}
            <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Employee:</strong> {{ $loan->employee->name ?? 'N/A' }} {{ $loan->employee->last_name ?? '' }} (ID: {{ $loan->employee->emp_id ?? 'N/A' }})</p>
                    <p class="mb-1"><strong>Department / Branch:</strong> {{ optional($loan->employee->department)->name ?? 'N/A' }} / {{ optional($loan->employee->branch)->branch_name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Loan Type:</strong> <span class="badge bg-info text-dark">{{ $loan->loanType->name ?? 'Staff Loan' }}</span></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>Principal Amount:</strong> <span class="fw-bold text-dark fs-6">৳ {{ number_format($loan->amount, 2) }}</span></p>
                    <p class="mb-1"><strong>Interest Rate:</strong> {{ $loan->interest_rate }} %</p>
                    <p class="mb-1"><strong>Repayment Term:</strong> {{ $loan->installments }} Months</p>
                    <p class="mb-0"><strong>Monthly EMI:</strong> <span class="fw-bold text-danger fs-5">৳ {{ number_format($loan->monthly_installment, 2) }}</span></p>
                </div>
            </div>

            {{-- Action Buttons for Approvers / Finance --}}
            @if(isSuperAdmin() || can('approve_loans') || can('disburse_loans'))
                <div class="d-flex gap-2 mb-4 p-3 bg-white border rounded-3 justify-content-end flex-wrap">
                    @if($loan->status === 'Pending')
                        <form action="{{ route('employeeLoans.approve', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4"><i class="im im-icon-Check me-1"></i> Approve Loan</button>
                        </form>
                        <form action="{{ route('employeeLoans.reject', $loan->id) }}" method="POST" onsubmit="return confirm('Reject this loan application?')">
                            @csrf
                            <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="im im-icon-Close me-1"></i> Reject Loan</button>
                        </form>
                    @elseif($loan->status === 'Approved')
                        <form action="{{ route('employeeLoans.disburse', $loan->id) }}" method="POST" onsubmit="return confirm('Disburse this loan and generate EMI repayment schedules?')">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="im im-icon-Coins me-1"></i> Disburse Funds & Generate EMI Schedule</button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Repayment Schedule Table --}}
            <h5 class="fw-bold mb-3"><i class="im im-icon-Calendar me-2 text-primary"></i>Repayment EMI Schedule</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle border mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Installment #</th>
                            <th>Due Date</th>
                            <th>Principal (৳)</th>
                            <th>Interest (৳)</th>
                            <th>Total EMI (৳)</th>
                            <th>Paid (৳)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $sch)
                            <tr>
                                <td class="fw-bold"># {{ $sch->installment_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($sch->due_date)->format('d M, Y') }}</td>
                                <td>৳ {{ number_format($sch->principal_amount, 2) }}</td>
                                <td>৳ {{ number_format($sch->interest_amount, 2) }}</td>
                                <td class="fw-bold text-dark">৳ {{ number_format($sch->total_installment, 2) }}</td>
                                <td class="text-success fw-bold">৳ {{ number_format($sch->paid_amount, 2) }}</td>
                                <td>
                                    @if($sch->status === 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No EMI schedules generated yet. Schedules are automatically created upon disbursement.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
