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
                    <span class="badge bg-success fs-6 px-3 py-2"><i class="im im-icon-Yes me-1"></i> Disbursed</span>
                @elseif($loan->status === 'Active Repayment')
                    <span class="badge bg-primary fs-6 px-3 py-2"><i class="im im-icon-Sync me-1"></i> Active Repayment</span>
                @elseif($loan->status === 'Completed')
                    <span class="badge bg-dark fs-6 px-3 py-2"><i class="im im-icon-Ok me-1"></i> Fully Repaid / Completed</span>
                @else
                    <span class="badge bg-danger fs-6 px-3 py-2"><i class="im im-icon-Close me-1"></i> {{ $loan->status }}</span>
                @endif
            </div>
        </div>
        <div class="card-body p-4">
            {{-- Employee & Loan Header Info --}}
            <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Employee:</strong> {{ $loan->employee->name ?? 'N/A' }} {{ $loan->employee->last_name ?? '' }} (ID: {{ $loan->employee->emp_id ?? 'N/A' }})</p>
                    <p class="mb-1"><strong>Salary Grade:</strong> {{ $loan->employee->salaryGrade->grade ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Department / Branch:</strong> {{ optional($loan->employee->department)->name ?? 'N/A' }} / {{ optional($loan->employee->branch)->branch_name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Loan Category / Type:</strong> <span class="badge bg-info text-dark">{{ $loan->loanType->name ?? 'Staff Loan' }}</span></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>Principal Amount:</strong> <span class="fw-bold text-dark fs-6">৳ {{ number_format($loan->amount, 2) }}</span></p>
                    <p class="mb-1"><strong>Interest Rate:</strong> {{ $loan->interest_rate }} %</p>
                    <p class="mb-1"><strong>Repayment Term:</strong> {{ $loan->installments }} Months (Paid: {{ $loan->paid_installments ?? 0 }})</p>
                    <p class="mb-0"><strong>Monthly EMI:</strong> <span class="fw-bold text-danger fs-5">৳ {{ number_format($loan->monthly_installment, 2) }}</span></p>
                </div>
            </div>

            {{-- Scheduling & Balance Details --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border border-primary-subtle bg-primary-subtle bg-opacity-10 rounded-3 p-3 text-center">
                        <small class="text-muted d-block font-semibold">Loan Required Month</small>
                        <strong class="text-primary fs-6">{{ $loan->required_month ? \Carbon\Carbon::parse($loan->required_month)->format('F Y') : 'N/A' }}</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border border-success-subtle bg-success-subtle bg-opacity-10 rounded-3 p-3 text-center">
                        <small class="text-muted d-block font-semibold">Loan Effective Month</small>
                        <strong class="text-success fs-6">{{ $loan->effective_month ? \Carbon\Carbon::parse($loan->effective_month)->format('F Y') : 'N/A' }}</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border border-warning-subtle bg-warning-subtle bg-opacity-10 rounded-3 p-3 text-center">
                        <small class="text-muted d-block font-semibold">Total Paid Amount</small>
                        <strong class="text-warning fs-6">৳ {{ number_format($loan->paid_amount ?? 0, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border border-danger-subtle bg-danger-subtle bg-opacity-10 rounded-3 p-3 text-center">
                        <small class="text-muted d-block font-semibold">Outstanding Balance</small>
                        <strong class="text-danger fs-6">৳ {{ number_format($loan->outstanding_balance, 2) }}</strong>
                    </div>
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

            {{-- Repayment History Audit Trail --}}
            <h5 class="fw-bold mb-3 mt-4"><i class="im im-icon-Receipt me-2 text-success"></i>Payroll Loan Repayment Audit History</h5>
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle border mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Payroll Month</th>
                            <th>Deduction Amount</th>
                            <th>Principal Paid</th>
                            <th>Remaining Balance</th>
                            <th>Payroll Sheet Ref</th>
                            <th>Batch ID</th>
                            <th>Deducted Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repayments as $index => $rep)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-primary">{{ \Carbon\Carbon::parse($rep->payroll_month)->format('M Y') }}</span></td>
                                <td class="fw-bold text-danger">৳ {{ number_format($rep->installment_amount > 0 ? $rep->installment_amount : $rep->amount, 2) }}</td>
                                <td>৳ {{ number_format($rep->principal_paid > 0 ? $rep->principal_paid : $rep->amount, 2) }}</td>
                                <td class="fw-bold text-dark">৳ {{ number_format($rep->remaining_balance, 2) }}</td>
                                <td><small class="font-monospace text-muted">PAYROLL-#{{ $rep->salary_sheet_reference ?? 'N/A' }}</small></td>
                                <td><small class="badge bg-light text-dark border">{{ $rep->payroll_batch_id ?? 'N/A' }}</small></td>
                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($rep->created_at)->format('d M Y, h:i A') }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No payroll deductions recorded yet. Deductions will appear automatically when salary is processed on or after the Loan Effective Month ({{ $loan->effective_month ? \Carbon\Carbon::parse($loan->effective_month)->format('F Y') : 'N/A' }}).</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Repayment Schedule Table --}}
            <h5 class="fw-bold mb-3"><i class="im im-icon-Calendar me-2 text-primary"></i>Repayment EMI Schedule Breakdown</h5>
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
        <div class="card-footer bg-white py-3">
            <a href="{{ route('employeeLoans.index') }}" class="btn btn-secondary rounded-pill px-4"><i class="im im-icon-Left me-1"></i> Back to Loan List</a>
        </div>
    </div>
</div>
@endsection
