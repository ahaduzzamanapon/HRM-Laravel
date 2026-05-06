@extends('layouts.default')
@section('title', 'Smart Movement — Admin')
@section('content')

<style>
.sm-stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 24px 20px;
    color: #fff;
    border: none;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(102,126,234,.35);
}
.sm-stat-card.green  { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 8px 24px rgba(56,239,125,.25); }
.sm-stat-card.orange { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); box-shadow: 0 8px 24px rgba(255,210,0,.25); }
.sm-stat-card.blue   { background: linear-gradient(135deg, #0177bc 0%, #00c6ff 100%); box-shadow: 0 8px 24px rgba(1,119,188,.25); }
.sm-stat-card .icon-bg {
    position: absolute; right: -10px; top: -10px;
    font-size: 70px; opacity: .15;
}
.sm-stat-card .label { font-size: 12px; font-weight: 600; letter-spacing: .8px; text-transform: uppercase; opacity: .85; }
.sm-stat-card .value { font-size: 26px; font-weight: 700; margin-top: 4px; }
.sm-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: .4px; }
.sm-badge.pending    { background: #fff3cd; color: #856404; }
.sm-badge.active     { background: #d1e7dd; color: #0f5132; }
.sm-badge.completed  { background: #e2e3e5; color: #41464b; }
.sm-badge.approved   { background: #cff4fc; color: #055160; }
.sm-badge.handed_over{ background: #cfe2ff; color: #084298; }
.sm-badge.hr_approved{ background: #d1ecf1; color: #0c5460; }
.sm-badge.not_applied{ background: #f8d7da; color: #842029; }
.move-row:hover { background: #f0f7ff !important; }
.page-header-bar {
    background: linear-gradient(90deg, #0177bc 0%, #00c6ff 100%);
    border-radius: 14px;
    padding: 18px 24px;
    color: #fff;
    margin-bottom: 24px;
}
</style>

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fa fa-car me-2"></i>Smart Movement — Admin Dashboard</h4>
        <small style="opacity:.8">Manage employee field movements and travel allowances</small>
    </div>
    <div>
        <a href="{{ route('new-movement.ta-list') }}" class="btn btn-light btn-sm me-2"><i class="fa fa-list me-1"></i>TA List</a>
        <a href="{{ route('new-movement.ta-summary') }}" class="btn btn-light btn-sm"><i class="fa fa-chart-bar me-1"></i>TA Summary</a>
    </div>
</div>

{{-- Stats Row --}}
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
            <div class="label">Pending TA</div>
            <div class="value">৳{{ number_format($stats['pending_ta'], 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sm-stat-card green">
            <div class="icon-bg"><i class="fa fa-check"></i></div>
            <div class="label">Approved TA</div>
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

{{-- Movements Table --}}
<div class="card shadow border-0" style="border-radius:14px;overflow:hidden;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4">
        <span class="fw-bold text-dark"><i class="fa fa-map-marker text-primary me-2"></i>All Movements <span class="badge bg-primary ms-1">{{ $movements->count() }}</span></span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13.5px;">
            <thead style="background:#f4f7fb;">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th>Employee</th>
                    <th>Start Location</th>
                    <th>Started</th>
                    <th>Current Status</th>
                    <th>TA</th>
                    <th class="text-center">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr class="move-row">
                    <td class="px-4">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($m->user->name ?? 'U', 0,1)) }}
                            </div>
                            <span>{{ $m->user->name ?? '—' }} {{ $m->user->last_name ?? '' }}</span>
                        </div>
                    </td>
                    <td><i class="fa fa-map-marker text-primary me-1"></i>{{ $m->start_location }}</td>
                    <td class="text-muted" style="font-size:12px;">{{ $m->start_time ? \Carbon\Carbon::parse($m->start_time)->format('d M y, h:i A') : '—' }}</td>
                    <td>
                        <div><i class="fa {{ $m->status_icon ?? 'fa-circle' }} me-1 text-primary"></i><strong>{{ $m->current_status_text ?? ucfirst($m->status) }}</strong></div>
                        <small class="text-muted">{{ Str::limit($m->current_location ?? '', 35) }}</small>
                    </td>
                    <td>
                        @php $tcMap = ['not_applied'=>'not_applied','pending'=>'pending','hr_approved'=>'hr_approved','approved'=>'approved','handed_over'=>'handed_over','active'=>'active','completed'=>'completed']; @endphp
                        <span class="sm-badge {{ $tcMap[$m->ta_status] ?? 'completed' }}">{{ str_replace('_',' ', ucfirst($m->ta_status)) }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('new-movement.details', $m->id) }}" class="btn btn-sm" style="background:#e8f0ff;color:#0177bc;border-radius:8px;"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="fa fa-car" style="font-size:36px;opacity:.25;"></i><br>
                        <span class="mt-2 d-block">No movements recorded yet.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
