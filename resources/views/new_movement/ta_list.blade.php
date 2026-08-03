@extends('layouts.default')
@section('title', 'TA List')
@section('content')

<style>
.page-bar { background:linear-gradient(90deg,#0177bc,#00c6ff); border-radius:14px; padding:16px 24px; color:#fff; margin-bottom:24px; }
.filters-card { background:#fff; border-radius:12px; padding:16px 20px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:20px; }
.filters-card .form-control, .filters-card .form-select { border:2px solid #e8edf5; border-radius:8px; font-size:13px; padding:7px 12px; }
.filters-card .form-control:focus, .filters-card .form-select:focus { border-color:#0177bc; box-shadow:none; }
.sm-badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700; }
.sm-badge.pending    { background:#fff3cd;color:#856404; }
.sm-badge.hr_approved{ background:#d1ecf1;color:#0c5460; }
.sm-badge.approved   { background:#cff4fc;color:#055160; }
.sm-badge.handed_over{ background:#cfe2ff;color:#084298; }
.sm-badge.not_applied{ background:#f8d7da;color:#842029; }
.data-table th { font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#888;padding:11px 16px;background:#f8f9fa;font-weight:700;border-bottom:2px solid #eee; }
.data-table td { padding:11px 16px;font-size:13.5px;vertical-align:middle;border-bottom:1px solid #f4f4f4; }
.data-table tr:hover td { background:#f0f7ff; }
.data-table tr:last-child td { border:none; }
</style>

<div class="page-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0"><i class="fa fa-list me-2"></i>Travel Allowance — All Applications</h5>
        <small style="opacity:.8;">Review and track employee TA requests</small>
    </div>
    <a href="{{ route('new-movement.index') }}" class="btn btn-light btn-sm fw-semibold"><i class="fa fa-arrow-left me-1"></i>Dashboard</a>
</div>

<div class="filters-card">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date"></div>
        <div class="col-md-2"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date"></div>
        <div class="col-md-2">
            <select name="ta_status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('ta_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="hr_approved" {{ request('ta_status') === 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
                <option value="approved" {{ request('ta_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="handed_over" {{ request('ta_status') === 'handed_over' ? 'selected' : '' }}>Handed Over</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="employee_id" class="form-select">
                <option value="">All Employees</option>
                @foreach($employees as $e)
                <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->name }} {{ $e->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1"><button type="submit" class="btn btn-primary w-100" style="border-radius:8px;">Filter</button></div>
        <div class="col-md-1"><a href="{{ route('new-movement.ta-list') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Reset</a></div>
    </form>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
    <div class="table-responsive">
        <table class="data-table table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Branch</th>
                    <th>Start Location</th>
                    <th>Date</th>
                    <th>TA Status</th>
                    <th>Applied (৳)</th>
                    <th>Approved (৳)</th>
                    <th>Processed By</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;">
                                {{ strtoupper(substr($m->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $m->user->name ?? '—' }} {{ $m->user->last_name ?? '' }}</strong>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-secondary border">{{ $m->user->branch->name ?? 'Head Office' }}</span>
                    </td>
                    <td><i class="fa fa-map-marker text-primary me-1"></i>{{ $m->start_location }}</td>
                    <td class="text-muted" style="font-size:12px;">{{ $m->start_time ? \Carbon\Carbon::parse($m->start_time)->format('d M Y') : '—' }}</td>
                    <td><span class="sm-badge {{ $m->ta_status }}">{{ str_replace('_',' ',ucfirst($m->ta_status)) }}</span></td>
                    <td class="fw-bold text-primary">৳{{ number_format($m->ta_amount, 2) }}</td>
                    <td class="fw-bold text-success">৳{{ number_format($m->ta_app_amt, 2) }}</td>
                    <td>
                        @if($m->updater)
                            <div style="font-size:12px;" class="fw-semibold text-dark">
                                {{ $m->updater->name }} {{ $m->updater->last_name }}
                            </div>
                            <small class="text-muted">{{ $m->updater->branch->name ?? 'Head Office' }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="action-buttons-group justify-content-center">
                            @include('layouts.partials.action_buttons', [
                                'showEdit' => false,
                                'showDelete' => false,
                                'viewRoute' => route('new-movement.details', $m->id)
                            ])
                            <a href="{{ route('new-movement.details', $m->id) }}#apply-ta" class="btn-action btn-action-approve" title="Approve / Review" data-bs-toggle="tooltip">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-5 text-muted"><i class="fa fa-list" style="font-size:36px;opacity:.2;"></i><p class="mt-2 mb-0">No TA records found.</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
