@extends('layouts.default')
@section('title', 'What Next?')
@section('content')

<style>
.decision-header { text-align:center; margin-bottom:28px; }
.decision-header h4 { font-weight:700; color:#1a1a2e; }
.option-card { border:2px solid #e8edf5; border-radius:14px; padding:20px 22px; margin-bottom:14px; display:flex; align-items:center; gap:16px; cursor:pointer; transition:all .2s; background:#fff; text-decoration:none; color:inherit; width:100%; }
.option-card:hover, .option-card:focus { border-color:#0177bc; background:#f0f7ff; transform:translateX(4px); text-decoration:none; color:inherit; }
.option-card .opt-icon { width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0; }
.option-card .opt-icon.meeting { background:#e8f0ff; color:#0177bc; }
.option-card .opt-icon.visit   { background:#f3f0ff; color:#764ba2; }
.option-card .opt-icon.office  { background:#d1e7dd; color:#0f5132; }
.option-card .opt-icon.home    { background:#fff3cd; color:#856404; }
.option-card .opt-text strong { font-size:14px;font-weight:700;display:block; }
.option-card .opt-text small { color:#888; font-size:12px; }
.gps-bar { background:#f4f7fb;border-radius:10px;padding:10px 16px;font-size:12px;color:#666;text-align:center;margin-bottom:20px; }
</style>

<div class="row justify-content-center py-3">
    <div class="col-md-5 col-lg-4">
        <div class="decision-header">
            <h4><i class="fa fa-question-circle text-primary me-2"></i>What would you like to do next?</h4>
            <p class="text-muted" style="font-size:13px;">You've reached your destination. Choose your next action.</p>
        </div>

        <div class="gps-bar" id="gps-status"><i class="fa fa-crosshairs me-1"></i>Detecting location…</div>

        {{-- Meeting --}}
        <form method="POST" action="{{ route('new-movement.handle-decision') }}">
            @csrf
            <input type="hidden" name="choice" value="meeting">
            <input type="hidden" name="current_location" class="c-loc">
            <input type="hidden" name="latitude" class="c-lat">
            <input type="hidden" name="longitude" class="c-lng">
            <button type="submit" class="option-card">
                <span class="opt-icon meeting"><i class="fa fa-handshake-o"></i></span>
                <span class="opt-text"><strong>Go to Next Meeting</strong><small>Log another client meeting</small></span>
                <i class="fa fa-chevron-right ms-auto text-muted"></i>
            </button>
        </form>

        {{-- Visit --}}
        <form method="POST" action="{{ route('new-movement.handle-decision') }}">
            @csrf
            <input type="hidden" name="choice" value="visit">
            <input type="hidden" name="current_location" class="c-loc">
            <input type="hidden" name="latitude" class="c-lat">
            <input type="hidden" name="longitude" class="c-lng">
            <button type="submit" class="option-card">
                <span class="opt-icon visit"><i class="fa fa-eye"></i></span>
                <span class="opt-text"><strong>Log Client Visit</strong><small>Client was busy – no meeting</small></span>
                <i class="fa fa-chevron-right ms-auto text-muted"></i>
            </button>
        </form>

        {{-- Office --}}
        <form method="POST" action="{{ route('new-movement.handle-decision') }}">
            @csrf
            <input type="hidden" name="choice" value="office">
            <input type="hidden" name="current_location" class="c-loc">
            <input type="hidden" name="latitude" class="c-lat">
            <input type="hidden" name="longitude" class="c-lng">
            <button type="submit" class="option-card">
                <span class="opt-icon office"><i class="fa fa-building"></i></span>
                <span class="opt-text"><strong>Return to Office</strong><small>Head back and end movement on arrival</small></span>
                <i class="fa fa-chevron-right ms-auto text-muted"></i>
            </button>
        </form>

        {{-- Home --}}
        <form method="POST" action="{{ route('new-movement.handle-decision') }}">
            @csrf
            <input type="hidden" name="choice" value="home">
            <input type="hidden" name="current_location" class="c-loc">
            <input type="hidden" name="latitude" class="c-lat">
            <input type="hidden" name="longitude" class="c-lng">
            <button type="submit" class="option-card">
                <span class="opt-icon home"><i class="fa fa-home"></i></span>
                <span class="opt-text"><strong>Go Home</strong><small>End field work for the day</small></span>
                <i class="fa fa-chevron-right ms-auto text-muted"></i>
            </button>
        </form>
    </div>
</div>

<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
        document.querySelectorAll('.c-lat').forEach(i => i.value = pos.coords.latitude);
        document.querySelectorAll('.c-lng').forEach(i => i.value = pos.coords.longitude);
        const loc = pos.coords.latitude.toFixed(5) + ', ' + pos.coords.longitude.toFixed(5);
        document.querySelectorAll('.c-loc').forEach(i => i.value = loc);
        document.getElementById('gps-status').innerHTML = '<i class="fa fa-crosshairs me-1" style="color:#11998e;"></i>' + loc;
    });
}
</script>
@endsection
