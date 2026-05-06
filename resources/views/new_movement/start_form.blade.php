@extends('layouts.default')
@section('title', 'Start Movement')
@section('content')

<style>
.sm-form-card { background:#fff; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.08); overflow:hidden; }
.sm-form-header { background:linear-gradient(135deg,#0177bc,#00c6ff); padding:28px 32px; color:#fff; }
.sm-form-body { padding:28px 32px; }
.sm-input { border:2px solid #e8edf5; border-radius:10px; padding:10px 14px; transition:border-color .2s; width:100%; font-size:14px; }
.sm-input:focus { border-color:#0177bc; outline:none; box-shadow:0 0 0 4px rgba(1,119,188,.08); }
.sm-label { font-weight:600; font-size:13px; color:#555; margin-bottom:6px; display:block; }
.sm-btn-primary { background:linear-gradient(135deg,#0177bc,#00c6ff); color:#fff; border:none; border-radius:10px; padding:12px 24px; font-weight:700; font-size:15px; width:100%; cursor:pointer; transition:transform .15s, box-shadow .2s; box-shadow:0 4px 14px rgba(1,119,188,.3); }
.sm-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(1,119,188,.4); color:#fff; }
.gps-pill { background:#e8f0ff; border:none; border-radius:8px; padding:6px 14px; color:#0177bc; font-size:12px; font-weight:600; cursor:pointer; }
.gps-pill:hover { background:#0177bc; color:#fff; }
</style>

<div class="row justify-content-center py-3">
    <div class="col-md-6 col-lg-5">
        <div class="sm-form-card">
            <div class="sm-form-header">
                <h5 class="mb-1 fw-bold"><i class="fa fa-map-marker me-2"></i>Start a New Movement</h5>
                <small style="opacity:.8;">Fill in your departure details below</small>
            </div>
            <form method="POST" action="{{ route('new-movement.start.process') }}" enctype="multipart/form-data" class="sm-form-body">
                @csrf
                <input type="hidden" name="latitude"  id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="mb-3">
                    <label class="sm-label">Start Location <span class="text-danger">*</span></label>
                    <input type="text" name="start_location" id="start_location" class="sm-input" required placeholder="e.g. Head Office, Dhaka">
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <button type="button" class="gps-pill" onclick="getLocation()"><i class="fa fa-crosshairs me-1"></i>Auto-detect GPS</button>
                        <small class="text-muted" id="gps-status"></small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="sm-label">Purpose</label>
                    <input type="text" name="purpose" class="sm-input" placeholder="Client visit, Field work, Survey…">
                </div>

                <div class="mb-3">
                    <label class="sm-label">Movement Type</label>
                    <select name="type" class="sm-input">
                        <option value="official">Official</option>
                        <option value="personal">Personal</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="sm-label">Start Photo <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="file" name="start_photo" class="sm-input" accept="image/*" capture="environment" style="padding:8px 14px;">
                </div>

                <button type="submit" class="sm-btn-primary"><i class="fa fa-play me-2"></i>Start Movement</button>
            </form>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('new-movement.my-dashboard') }}" class="text-muted" style="font-size:13px;"><i class="fa fa-arrow-left me-1"></i>Back to My Movements</a>
        </div>
    </div>
</div>

<script>
function getLocation() {
    const status = document.getElementById('gps-status');
    status.textContent = 'Detecting…';
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
            const loc = document.getElementById('start_location');
            if (!loc.value) loc.value = pos.coords.latitude.toFixed(5) + ', ' + pos.coords.longitude.toFixed(5);
            status.innerHTML = '<span style="color:#11998e;">✓ Location acquired</span>';
        }, function() { status.innerHTML = '<span style="color:#dc3545;">✗ Could not get location</span>'; });
    }
}
getLocation();
</script>
@endsection
