@extends('layouts.default')
@section('title', 'Movement Details')
@section('content')

<style>
.detail-header { background:linear-gradient(135deg,#0177bc,#00c6ff); border-radius:16px; padding:24px 28px; color:#fff; margin-bottom:24px; }
.detail-section { background:#fff; border-radius:14px; box-shadow:0 4px 16px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.detail-section .sec-head { padding:14px 20px; border-bottom:1px solid #f0f0f0; font-weight:700; font-size:14px; color:#1a1a2e; display:flex; align-items:center; gap:8px; }
.detail-section .sec-head .sec-icon { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#e8f0ff;color:#0177bc;font-size:14px; }
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
.info-cell { padding:12px 20px; border-bottom:1px solid #f4f4f4; }
.info-cell:nth-child(odd) { border-right:1px solid #f4f4f4; }
.info-cell .lbl { font-size:11px; font-weight:700; text-transform:uppercase; color:#888; letter-spacing:.5px; }
.info-cell .val { font-size:14px; font-weight:600; color:#1a1a2e; margin-top:2px; }
.sm-badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700; }
.sm-badge.active     { background:#d1e7dd;color:#0f5132; }
.sm-badge.completed  { background:#e2e3e5;color:#41464b; }
.sm-badge.pending    { background:#fff3cd;color:#856404; }
.sm-badge.approved   { background:#cff4fc;color:#055160; }
.sm-badge.handed_over{ background:#cfe2ff;color:#084298; }
.sm-badge.not_applied{ background:#f8d7da;color:#842029; }
.mini-table th { font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#888; padding:10px 16px; background:#f8f9fa; font-weight:700; }
.mini-table td { padding:10px 16px; font-size:13px; border-bottom:1px solid #f4f4f4; vertical-align:middle; }
.mini-table tr:last-child td { border:none; }
.timeline-dot { width:10px;height:10px;border-radius:50%;background:#0177bc;display:inline-block;margin-right:6px; }
.ta-apply-card { background:#fffdf0; border:2px dashed #ffd200; border-radius:12px; padding:20px; }
.sm-input { border:2px solid #e8edf5; border-radius:8px; padding:8px 12px; font-size:13px; width:100%; }
.sm-input:focus { border-color:#0177bc; outline:none; }
</style>

{{-- Header --}}
<div class="detail-header d-flex justify-content-between align-items-start gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1"><i class="fa fa-car me-2"></i>Movement #{{ $movement->id }}</h5>
        <p class="mb-0" style="opacity:.85;font-size:13px;">
            <i class="fa fa-user me-1"></i>{{ $movement->user->name ?? '—' }} {{ $movement->user->last_name ?? '' }}
            &nbsp;·&nbsp;
            <i class="fa fa-clock-o me-1"></i>{{ $movement->start_time ? \Carbon\Carbon::parse($movement->start_time)->format('d M Y, h:i A') : '—' }}
        </p>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-light btn-sm fw-semibold"><i class="fa fa-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-3">
    {{-- Left col --}}
    <div class="col-md-6">
        {{-- Movement Info --}}
        <div class="detail-section">
            <div class="sec-head"><span class="sec-icon"><i class="fa fa-info"></i></span>Movement Info</div>
            <div class="info-grid">
                <div class="info-cell"><div class="lbl">Status</div><div class="val"><span class="sm-badge {{ $movement->status }}">{{ ucfirst($movement->status) }}</span></div></div>
                <div class="info-cell"><div class="lbl">Purpose</div><div class="val">{{ $movement->purpose ?? '—' }}</div></div>
                <div class="info-cell"><div class="lbl">Start Location</div><div class="val">{{ $movement->start_location }}</div></div>
                <div class="info-cell"><div class="lbl">Type</div><div class="val">{{ ucfirst($movement->type ?? '—') }}</div></div>
                <div class="info-cell"><div class="lbl">Started</div><div class="val">{{ $movement->start_time ? \Carbon\Carbon::parse($movement->start_time)->format('d M y, h:i A') : '—' }}</div></div>
                <div class="info-cell"><div class="lbl">Ended</div><div class="val">{{ $movement->end_time ? \Carbon\Carbon::parse($movement->end_time)->format('d M y, h:i A') : '<span class="text-success">Active</span>' }}</div></div>
            </div>
        </div>

        {{-- TA Info --}}
        <div class="detail-section">
            <div class="sec-head"><span class="sec-icon"><i class="fa fa-money"></i></span>Travel Allowance (TA)</div>
            <div class="info-grid">
                <div class="info-cell"><div class="lbl">TA Status</div><div class="val"><span class="sm-badge {{ $movement->ta_status }}">{{ str_replace('_',' ',ucfirst($movement->ta_status)) }}</span></div></div>
                <div class="info-cell"><div class="lbl">Applied</div><div class="val fw-bold">৳{{ number_format($movement->ta_amount, 2) }}</div></div>
                <div class="info-cell"><div class="lbl">Approved</div><div class="val fw-bold text-success">৳{{ number_format($movement->ta_app_amt, 2) }}</div></div>
                <div class="info-cell"><div class="lbl">Expenses</div><div class="val">{{ $movement->expenses->count() }} item(s)</div></div>
            </div>
        </div>
    </div>

    {{-- Right col --}}
    <div class="col-md-6">
        {{-- Travels --}}
        <div class="detail-section">
            <div class="sec-head"><span class="sec-icon"><i class="fa fa-road"></i></span>Travel Legs <span class="ms-auto text-muted fw-normal" style="font-size:12px;">{{ $movement->travels->count() }} legs</span></div>
            <table class="mini-table w-100">
                <thead><tr><th>From</th><th>To</th><th>KM</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($movement->travels as $t)
                    <tr>
                        <td>{{ $t->from_location }}</td>
                        <td>{{ $t->to_location ?? '—' }}</td>
                        <td>{{ $t->distance_km ?? '—' }}</td>
                        <td><span class="sm-badge {{ $t->status === 'running' ? 'active' : 'completed' }}">{{ ucfirst($t->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No travel legs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Meetings --}}
        <div class="detail-section">
            <div class="sec-head"><span class="sec-icon"><i class="fa fa-users"></i></span>Meetings &amp; Visits <span class="ms-auto text-muted fw-normal" style="font-size:12px;">{{ $movement->meetings->count() }} total</span></div>
            <table class="mini-table w-100">
                <thead><tr><th>Client</th><th>Type</th><th>Feedback</th></tr></thead>
                <tbody>
                    @forelse($movement->meetings as $m)
                    <tr>
                        <td><strong>{{ $m->client_name ?? '—' }}</strong><br><small class="text-muted">{{ $m->contact_person ?? '' }}</small></td>
                        <td>{{ ucfirst($m->meeting_type ?? '—') }}</td>
                        <td style="max-width:160px;white-space:normal;">{{ $m->feedback ?? '<span class="text-muted">—</span>' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No meetings.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Apply TA section --}}
@if($movement->employee_id === auth()->id() && $movement->ta_status === 'not_applied')
<div class="ta-apply-card mt-2" id="apply-ta">
    <h6 class="fw-bold mb-3"><i class="fa fa-money text-warning me-2"></i>Apply for Travel Allowance</h6>
    <form method="POST" action="{{ route('new-movement.apply-ta', $movement->id) }}">
        @csrf
        @forelse($movement->travels as $t)
        <div class="row g-2 align-items-end mb-3 pb-3 border-bottom">
            <div class="col-12 mb-1">
                <small class="fw-bold text-muted"><i class="fa fa-road me-1"></i>Leg {{ $loop->iteration }}: {{ $t->from_location }} → {{ $t->to_location ?? '?' }}</small>
            </div>
            <input type="hidden" name="expenses[{{ $loop->index }}][travel_id]" value="{{ $t->id }}">
            <div class="col-md-4">
                <label style="font-size:12px;color:#666;">Transport Type</label>
                <select name="expenses[{{ $loop->index }}][type]" class="sm-input">
                    <option value="local_transport">Local Transport</option>
                    <option value="taxi">Taxi / Rickshaw</option>
                    <option value="fuel">Fuel</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size:12px;color:#666;">Amount (৳)</label>
                <input type="number" step="0.01" name="expenses[{{ $loop->index }}][amount]" class="sm-input" value="0" min="0">
            </div>
            <div class="col-md-5">
                <label style="font-size:12px;color:#666;">Note</label>
                <input type="text" name="expenses[{{ $loop->index }}][note]" class="sm-input" placeholder="Optional note">
            </div>
        </div>
        @empty
        <p class="text-muted">No travel legs found.</p>
        @endforelse
        <button type="submit" class="btn fw-bold text-white px-4 py-2" style="background:linear-gradient(135deg,#f7971e,#ffd200);border-radius:10px;border:none;"><i class="fa fa-paper-plane me-2"></i>Submit TA Application</button>
    </form>
</div>
@elseif($movement->expenses->count() > 0)
<div class="detail-section mt-2" id="apply-ta">
    <div class="sec-head"><span class="sec-icon" style="background:#fff3cd;color:#856404;"><i class="fa fa-money"></i></span>Expense Details</div>
    <table class="mini-table w-100">
        <thead><tr><th>Travel Leg</th><th>Type</th><th>Amount</th><th>Approved</th><th>Note</th></tr></thead>
        <tbody>
            @foreach($movement->expenses as $e)
            <tr>
                <td>{{ $e->travel->from_location ?? '—' }}</td>
                <td>{{ ucfirst($e->transport_type ?? '—') }}</td>
                <td>৳{{ number_format($e->amount, 2) }}</td>
                <td>৳{{ number_format($e->approve_amount, 2) }}</td>
                <td>{{ $e->note ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
