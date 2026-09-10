<div class="table-responsive">
    <table class="table table-hover align-middle border mb-0" id="loan-repayments-table">
        <thead class="table-dark">
            <tr>
                <th>Loan Application & Employee</th>
                <th>Category</th>
                <th>Repayment Amount</th>
                <th>Repayment Date</th>
                <th>Remarks / Notes</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($loanRepayments as $loanRepayment)
            @php
                $loan = $loanRepayment->loan;
                $empName = optional($loan->employee)->name ? (optional($loan->employee)->name . ' ' . (optional($loan->employee)->last_name ?? '')) : 'N/A';
                $appNo = optional($loan)->application_no ?? ('LN-' . ($loanRepayment->loan_id ?? 'N/A'));
                $branchName = optional(optional($loan)->employee)->branch->branch_name ?? 'N/A';
                $typeName = optional(optional($loan)->loanType)->name ?? 'Staff Loan';
                $repDate = $loanRepayment->repayment_date ? \Carbon\Carbon::parse($loanRepayment->repayment_date)->format('d M Y, h:i A') : '—';
            @endphp
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark font-monospace border fs-6 me-1">{{ $appNo }}</span>
                        <div>
                            <div class="fw-bold text-dark">{{ $empName }}</div>
                            <small class="text-muted"><i class="fa fa-building-o me-1"></i>{{ $branchName }}</small>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-info text-dark">{{ $typeName }}</span></td>
                <td><span class="badge bg-success fs-6 fw-bold p-2">৳ {{ number_format($loanRepayment->amount, 2) }}</span></td>
                <td><small class="fw-semibold text-secondary">{{ $repDate }}</small></td>
                <td><small class="text-dark">{{ $loanRepayment->remarks ?? 'N/A' }}</small></td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        @if($loanRepayment->loan_id)
                            <a href="{{ route('employeeLoans.show', $loanRepayment->loan_id) }}" class="btn btn-sm btn-outline-primary" title="View Full Loan Repayment History" data-bs-toggle="tooltip">
                                <i class="fa fa-history"></i> Loan History
                            </a>
                        @endif
                        @include('layouts.partials.action_buttons', [
                            'viewRoute' => route('loanRepayments.show', [$loanRepayment->id]),
                            'editRoute' => route('loanRepayments.edit', [$loanRepayment->id]),
                            'deleteRoute' => route('loanRepayments.destroy', [$loanRepayment->id]),
                        ])
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="im im-icon-Coins mb-2 opacity-50 display-6 d-block"></i>
                    No loan repayments recorded yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
