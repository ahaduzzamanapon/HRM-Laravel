@extends('layouts.default')
@section('title', 'My Movements')
@section('content')

<style>
.sm-stat-card { background:linear-gradient(135deg,#667eea,#764ba2); border-radius:14px; padding:20px; color:#fff; border:none; position:relative; overflow:hidden; box-shadow:0 6px 20px rgba(102,126,234,.3); }
.sm-stat-card.green  { background:linear-gradient(135deg,#11998e,#38ef7d); box-shadow:0 6px 20px rgba(56,239,125,.2); }
.sm-stat-card.orange { background:linear-gradient(135deg,#f7971e,#ffd200); box-shadow:0 6px 20px rgba(255,180,0,.2); }
.sm-stat-card.blue   { background:linear-gradient(135deg,#0177bc,#00c6ff); box-shadow:0 6px 20px rgba(1,119,188,.2); }
.sm-stat-card .icon-bg { position:absolute;right:-8px;top:-8px;font-size:60px;opacity:.15; }
.sm-stat-card .label { font-size:11px;font-weight:600;letter-spacing:.8px;text-transform:uppercase;opacity:.85; }
.sm-stat-card .value { font-size:22px;font-weight:700;margin-top:4px; }
.sm-badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700; }
.sm-badge.pending    { background:#fff3cd;color:#856404; }
.sm-badge.active     { background:#d1e7dd;color:#0f5132; }
.sm-badge.completed  { background:#e2e3e5;color:#41464b; }
.sm-badge.approved   { background:#cff4fc;color:#055160; }
.sm-badge.handed_over{ background:#cfe2ff;color:#084298; }
.sm-badge.not_applied{ background:#f8d7da;color:#842029; }
.hist-row:hover { background:#f0f7ff !important; }
.action-btn { border-start-movement: 1px solid #eee; }
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a1a2e;"><i class="fa fa-car me-2 text-primary"></i>My Movements</h4>
        <small class="text-muted">Track your field visits, meetings and travel allowances</small>
    </div>
    <a href="{{ route('new-movement.start') }}" class="btn text-white px-4 py-2 fw-semibold" style="background:linear-gradient(135deg,#0177bc,#00c6ff);border-radius:10px;box-shadow:0 4px 14px rgba(1,119,188,.3);">
        <i class="fa fa-plus me-2"></i>Start Movement
    </a>
</div>

@if(session('success'))
<div class="alert border-0 shadow-sm mb-3" style="background:#d1e7dd;color:#0f5132;border-radius:10px;"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="sm-stat-card blue">
            <div class="icon-bg"><i class="fa fa-money"></i></div>
            <div class="label">Total TA</div>
            <div class="value">৳{{ number_format($stats['total_ta'], 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sm-stat-card orange">
            <div class="icon-bg"><i class="fa fa-clock-o"></i></div>
            <div class="label">Pending</div>
            <div class="value">৳{{ number_format($stats['pending_ta'], 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sm-stat-card green">
            <div class="icon-bg"><i class="fa fa-check"></i></div>
            <div class="label">Approved</div>
            <div class="value">৳{{ number_format($stats['approved_ta'], 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sm-stat-card">
            <div class="icon-bg"><i class="fa fa-handshake-o"></i></div>
            <div class="label">Handed Over</div>
            <div class="value">৳{{ number_format($stats['handed_over_ta'], 0) }}</div>
        </div>
    </div>
</div>

{{-- Movement History --}}
<div class="card shadow border-0" style="border-radius:14px;overflow:hidden;">
    <div class="card-header bg-white py-3 px-4">
        <span class="fw-bold text-dark"><i class="fa fa-history text-primary me-2"></i>Movement History</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13.5px;">
            <thead style="background:#f4f7fb;">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th>Start Location</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>TA Status</th>
                    <th>TA Amount</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr class="hist-row">
                    <td class="px-4">{{ $loop->iteration }}</td>
                    <td><i class="fa fa-map-marker text-primary me-1"></i>{{ $m->start_location }}</td>
                    <td class="text-muted" style="font-size:12px;">{{ $m->start_time ? \Carbon\Carbon::parse($m->start_time)->format('d M Y') : '—' }}</td>
                    <td><span class="sm-badge {{ $m->status }}">{{ ucfirst($m->status) }}</span></td>
                    <td><span class="sm-badge {{ $m->ta_status }}">{{ str_replace('_',' ', ucfirst($m->ta_status)) }}</span></td>
                    <td class="fw-semibold">৳{{ number_format($m->ta_amount, 2) }}</td>
                    <td class="text-center">
                        <a href="{{ route('new-movement.details', $m->id) }}" class="btn btn-sm me-1" style="background:#e8f0ff;color:#0177bc;border-radius:8px;" title="View"><i class="fa fa-eye"></i></a>
                        @if($m->ta_status === 'not_applied' && $m->status === 'completed')
                        <a href="{{ route('new-movement.details', $m->id) }}#apply-ta" class="btn btn-sm" style="background:#fff3cd;color:#856404;border-radius:8px;" title="Apply TA"><i class="fa fa-money"></i></a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa fa-car" style="font-size:42px;color:#ccc;"></i>
                        <p class="text-muted mt-2 mb-0">No movements yet.</p>
                        <a href="{{ route('new-movement.start') }}" class="btn btn-primary btn-sm mt-3">Start Your First Movement</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
