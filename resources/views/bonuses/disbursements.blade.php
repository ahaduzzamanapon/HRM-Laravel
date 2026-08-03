@extends('layouts.default')

@section('title', 'Bonus Disbursements')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Bonus Disbursement Statements</h3>
            <p class="text-muted mb-0">Review, approve, and track employee bonus payments</p>
        </div>
        <a href="{{ route('bonuses.index') }}" class="btn btn-outline-primary">
            <i class="im im-icon-Gear me-1"></i> Bonus Rules & Settings
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('bonuses.disbursements') }}" class="row g-3 align-items-center">
                @if(isSuperAdmin())
                    <div class="col-md-3">
                        <select name="branch_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- All Branches --</option>
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <input type="month" name="bonus_month" class="form-control" value="{{ request('bonus_month') }}" onchange="this.form.submit()" placeholder="Filter Month">
                </div>
                <div class="col-md-3">
                    <select name="payment_status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Statuses --</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('payment_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-auto ms-auto">
                    <a href="{{ route('bonuses.disbursements') }}" class="btn btn-light btn-sm">Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Emp ID</th>
                            <th>Employee Name</th>
                            <th>Branch</th>
                            <th>Bonus Rule</th>
                            <th>Month</th>
                            <th class="text-end">Base Salary (BDT)</th>
                            <th class="text-end">Bonus Amount (BDT)</th>
                            <th class="text-center">Payment Status</th>
                            <th>Payment Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disbursements as $item)
                            <tr id="row-disbursement-{{ $item->id }}">
                                <td><code>{{ $item->user->emp_id ?? 'N/A' }}</code></td>
                                <td class="fw-bold">{{ $item->user->name ?? 'N/A' }} {{ $item->user->last_name ?? '' }}</td>
                                <td><span class="badge bg-secondary">{{ $item->branch->branch_name ?? 'N/A' }}</span></td>
                                <td>{{ $item->bonusSetting->title ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->bonus_month)->format('M Y') }}</td>
                                <td class="text-end font-monospace">{{ number_format($item->base_amount, 2) }}</td>
                                <td class="text-end font-monospace fw-bold text-success">{{ number_format($item->bonus_amount, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge status-badge-{{ $item->id }} {{ $item->payment_status == 'paid' ? 'bg-success' : ($item->payment_status == 'approved' ? 'bg-primary' : 'bg-warning text-dark') }}">
                                        {{ ucfirst($item->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->format('d M, Y') : 'Pending' }}</td>
                                <td class="text-end">
                                    @if($item->payment_status != 'paid')
                                        <button class="btn btn-sm btn-outline-success me-1 btn-change-status" data-id="{{ $item->id }}" data-status="paid">
                                            <i class="im im-icon-Yes"></i> Mark Paid
                                        </button>
                                        @if($item->payment_status == 'pending')
                                            <button class="btn btn-sm btn-outline-primary btn-change-status" data-id="{{ $item->id }}" data-status="approved">
                                                <i class="im im-icon-Check"></i> Approve
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-muted small"><i class="im im-icon-Yes text-success"></i> Complete</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="im im-icon-Information display-6 d-block mb-2 text-secondary"></i>
                                    No bonus disbursement records found. Process a bonus rule to disburse bonuses to employees.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($disbursements->hasPages())
            <div class="card-footer bg-transparent border-0 py-3">
                {{ $disbursements->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('.btn-change-status').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');

            if (!confirm(`Are you sure you want to mark this bonus as ${status}?`)) {
                return;
            }

            fetch(`{{ url('bonuses/disbursements') }}/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ payment_status: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(err => alert('Failed to update status.'));
        });
    });
});
</script>
@endsection
