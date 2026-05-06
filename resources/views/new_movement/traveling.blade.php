@extends('layouts.default')
@section('title', 'Traveling')
@section('content')

<style>
.travel-hero { background:linear-gradient(135deg,#0177bc,#00c6ff); border-radius:20px; padding:36px 24px; color:#fff; text-align:center; margin-bottom:24px; box-shadow:0 10px 30px rgba(1,119,188,.35); }
.travel-hero .icon-ring { width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:36px; animation:pulse 2s infinite; }
@keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(255,255,255,.4)} 50%{box-shadow:0 0 0 14px rgba(255,255,255,0)} }
.info-pill { background:rgba(255,255,255,.2); border-radius:20px; padding:6px 16px; display:inline-block; font-size:13px; margin-top:8px; }
.sm-btn-arrive { background:#fff; color:#0177bc; border:none; border-radius:12px; padding:14px 24px; font-weight:700; font-size:15px; width:100%; cursor:pointer; box-shadow:0 4px 14px rgba(0,0,0,.1); transition:transform .15s; }
.sm-btn-arrive:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.15); }
.coord-box { background:#f4f7fb; border-radius:10px; padding:10px 16px; font-size:12px; color:#666; text-align:center; }
</style>

<div class="row justify-content-center py-3">
    <div class="col-md-5 col-lg-4">
        <div class="travel-hero">
            <div class="icon-ring"><i class="fa fa-car"></i></div>
            <h5 class="fw-bold mb-1">You Are Traveling</h5>
            <p class="mb-1" style="opacity:.9;">
                <i class="fa fa-map-marker me-1"></i><strong>{{ $travel->from_location ?? '—' }}</strong>
                @if($travel->to_location) → <strong>{{ $travel->to_location }}</strong> @endif
            </p>
            <div class="info-pill"><i class="fa fa-clock-o me-1"></i>Since {{ $travel->start_time ? \Carbon\Carbon::parse($travel->start_time)->format('h:i A') : '—' }}</div>
        </div>

        <div class="coord-box mb-3" id="gps-status">Detecting your location…</div>

        <form method="POST" action="{{ route('new-movement.reached-destination') }}">
            @csrf
            <input type="hidden" name="latitude"         id="latitude">
            <input type="hidden" name="longitude"        id="longitude">
            <input type="hidden" name="current_location" id="current_location" value="{{ $travel->to_location ?? '' }}">
            <input type="hidden" name="is_office_return" value="{{ $travel->is_office_return ?? 0 }}">
            <input type="hidden" name="is_home_return"   value="{{ ($travel->to_location === 'Home') ? 1 : 0 }}">
            <button type="submit" class="sm-btn-arrive">
                <i class="fa fa-map-pin me-2"></i>I Have Reached My Destination
            </button>
        </form>
    </div>
</div>

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(pos) {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
            document.getElementById('gps-status').innerHTML = '<i class="fa fa-crosshairs text-success me-1"></i>' + pos.coords.latitude.toFixed(5) + ', ' + pos.coords.longitude.toFixed(5) + ' <span style="color:#11998e;font-weight:600;">● Live</span>';
        });
    }
}
getLocation();
</script>
@endsection
