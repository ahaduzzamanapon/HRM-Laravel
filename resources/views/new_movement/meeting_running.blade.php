@extends('layouts.default')
@section('title', 'Meeting In Progress')
@section('content')

<style>
.meeting-hero { background:linear-gradient(135deg,#f7971e,#ffd200); border-radius:20px; padding:36px 24px; color:#433400; text-align:center; margin-bottom:24px; box-shadow:0 10px 30px rgba(247,151,30,.3); }
.meeting-hero .icon-ring { width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:36px;animation:breathe 2.5s infinite ease-in-out; }
@keyframes breathe { 0%,100%{transform:scale(1)} 50%{transform:scale(1.08)} }
.info-card { background:#fff;border-radius:12px;padding:14px 20px;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:16px;font-size:13.5px; }
.info-card .info-row { display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #f0f0f0; }
.info-card .info-row:last-child { border:none; }
.info-card .info-label { color:#888;font-size:12px;font-weight:600;text-transform:uppercase; }
.sm-btn-end { background:linear-gradient(135deg,#dc3545,#ff6b6b); color:#fff; border:none; border-radius:12px; padding:14px 24px; font-weight:700; font-size:15px; width:100%; cursor:pointer; box-shadow:0 4px 14px rgba(220,53,69,.3); transition:transform .15s; }
.sm-btn-end:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(220,53,69,.4); color:#fff; }
.timer-badge { background:rgba(255,255,255,.4);border-radius:20px;padding:6px 18px;display:inline-block;font-weight:700;font-size:14px;margin-top:8px; }
</style>

<div class="row justify-content-center py-3">
    <div class="col-md-5 col-lg-4">
        <div class="meeting-hero">
            <div class="icon-ring"><i class="fa fa-users"></i></div>
            <h5 class="fw-bold mb-1">Meeting In Progress</h5>
            <p class="mb-1">With <strong>{{ $meeting->client_name ?? '—' }}</strong></p>
            <div class="timer-badge" id="timer">⏱ Running</div>
        </div>

        <div class="info-card">
            <div class="info-row"><span class="info-label">Contact</span><span>{{ $meeting->contact_person ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Phone</span><span>{{ $meeting->contact_phone ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Location</span><span>{{ $meeting->location ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Started at</span><span>{{ $meeting->start_time ? \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') : '—' }}</span></div>
            @if($meeting->remarks)
            <div class="info-row"><span class="info-label">Remarks</span><span>{{ $meeting->remarks }}</span></div>
            @endif
        </div>

        <form method="POST" action="{{ route('new-movement.end-meeting') }}">
            @csrf
            <button type="submit" class="sm-btn-end"><i class="fa fa-stop me-2"></i>End Meeting</button>
        </form>
    </div>
</div>

<script>
const startTime = new Date('{{ $meeting->start_time }}');
function updateTimer() {
    const diff = Math.floor((Date.now() - startTime.getTime()) / 1000);
    const h = String(Math.floor(diff/3600)).padStart(2,'0');
    const m = String(Math.floor((diff%3600)/60)).padStart(2,'0');
    const s = String(diff%60).padStart(2,'0');
    document.getElementById('timer').textContent = '⏱ ' + h + ':' + m + ':' + s;
}
setInterval(updateTimer, 1000);
updateTimer();
</script>
@endsection
