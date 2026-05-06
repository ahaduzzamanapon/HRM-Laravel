@extends('layouts.default')
@section('title', 'TA Summary')
@section('content')

<style>
.page-bar { background:linear-gradient(90deg,#11998e,#38ef7d); border-radius:14px; padding:16px 24px; color:#fff; margin-bottom:24px; }
.filters-card { background:#fff; border-radius:12px; padding:16px 20px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:20px; }
.filters-card .form-control, .filters-card .form-select { border:2px solid #e8edf5; border-radius:8px; font-size:13px; padding:7px 12px; }
.filters-card .form-control:focus, .filters-card .form-select:focus { border-color:#11998e; box-shadow:none; }
.data-table th { font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#888;padding:11px 16px;background:#f8f9fa;font-weight:700;border-bottom:2px solid #eee; }
.data-table td { padding:11px 16px;font-size:13.5px;vertical-align:middle;border-bottom:1px solid #f4f4f4; }
.data-table tr:hover td { background:#f4fff8; }
.data-table tfoot td { background:#f4fff8; font-weight:700; }
.data-table tr:last-child td { border-bottom:1px solid #f4f4f4; }
.avatar { width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff; background:linear-gradient(135deg,#11998e,#38ef7d); flex-shrink:0; }
</style>

<div class="page-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0"><i class="fa fa-chart-bar me-2"></i>TA Summary by Employee</h5>
        <small style="opacity:.85;">Grouped view of all travel allowance applications</small>
    </div>
    <a href="{{ route('new-movement.index') }}" class="btn btn-light btn-sm fw-semibold"><i class="fa fa-arrow-left me-1"></i>Dashboard</a>
</div>

<div class="filters-card">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2"><label style="font-size:12px;color:#888;">From</label><input type="date" name="start_date" value="{{ $startDate }}" class="form-control"></div>
        <div class="col-md-2"><label style="font-size:12px;color:#888;">To</label><input type="date" name="end_date" value="{{ $endDate }}" class="form-control"></div>
        <div class="col-md-2">
            <label style="font-size:12px;color:#888;">TA Status</label>
            <select name="ta_status" class="form-select">
                <option value="pending" {{ $taStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="hr_approved" {{ $taStatus === 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
                <option value="approved" {{ $taStatus === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="handed_over" {{ $taStatus === 'handed_over' ? 'selected' : '' }}>Handed Over</option>
            </select>
        </div>
        <div class="col-md-3">
            <label style="font-size:12px;color:#888;">Employee</label>
            <select name="employee_id" class="form-select">
                <option value="">All Employees</option>
                @foreach($employees as $e)
                <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->name }} {{ $e->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><label style="font-size:12px;color:#888;">&nbsp;</label><button type="submit" class="btn btn-success w-100" style="border-radius:8px;">Apply Filter</button></div>
    </form>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
    <div class="table-responsive">
        <table class="data-table table mb-0">
            <thead><tr><th>#</th><th>Employee</th><th>Movements</th><th>Applied (৳)</th><th>Approved (৳)</th><th class="text-center">Details</th></tr></thead>
            <tbody>
                @forelse($summary as $row)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar">{{ strtoupper(substr($row->user->name ?? 'U', 0, 1)) }}</div>
                            {{ $row->user->name ?? '—' }} {{ $row->user->last_name ?? '' }}
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark fw-semibold">{{ $row->total_movements }}</span></td>
                    <td class="fw-semibold">৳{{ number_format($row->applied_amount, 2) }}</td>
                    <td class="fw-semibold text-success">৳{{ number_format($row->approved_amount, 2) }}</td>
                    <td class="text-center">
                        <a href="{{ route('new-movement.ta-summary-details', ['employee_id' => $row->employee_id, 'start_date' => $startDate, 'end_date' => $endDate, 'ta_status' => $taStatus]) }}"
                           class="btn btn-sm fw-semibold" style="background:#e8f0ff;color:#0177bc;border-radius:8px;"><i class="fa fa-eye me-1"></i>View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="fa fa-chart-bar" style="font-size:36px;opacity:.2;"></i><p class="mt-2 mb-0">No TA summary data for selected filters.</p></td></tr>
                @endforelse
            </tbody>
            @if($summary->count())
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end pe-3">Total</td>
                    <td>৳{{ number_format($summary->sum('applied_amount'), 2) }}</td>
                    <td class="text-success">৳{{ number_format($summary->sum('approved_amount'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
