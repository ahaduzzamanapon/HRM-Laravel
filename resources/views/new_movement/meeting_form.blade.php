@extends('layouts.default')
@section('title', 'Start Meeting')
@section('content')

<style>
.sm-form-card { background:#fff; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.08); overflow:hidden; }
.sm-form-header { background:linear-gradient(135deg,#667eea,#764ba2); padding:24px 28px; color:#fff; }
.sm-form-body { padding:24px 28px; }
.sm-input { border:2px solid #e8edf5; border-radius:10px; padding:9px 14px; transition:border-color .2s; width:100%; font-size:13.5px; background:#fff; }
.sm-input:focus { border-color:#667eea; outline:none; box-shadow:0 0 0 4px rgba(102,126,234,.08); }
.sm-label { font-weight:600; font-size:12.5px; color:#555; margin-bottom:5px; display:block; }
.sm-btn-purple { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; border:none; border-radius:10px; padding:12px 24px; font-weight:700; font-size:15px; width:100%; cursor:pointer; box-shadow:0 4px 14px rgba(102,126,234,.3); transition:transform .15s; }
.sm-btn-purple:hover { transform:translateY(-1px); color:#fff; }
.gps-pill { background:#f3f0ff; border:none; border-radius:8px; padding:6px 14px; color:#764ba2; font-size:12px; font-weight:600; cursor:pointer; }
.gps-pill:hover { background:#764ba2; color:#fff; }
</style>

<div class="row justify-content-center py-3">
    <div class="col-md-7 col-lg-6">
        <div class="sm-form-card">
            <div class="sm-form-header">
                <h5 class="mb-1 fw-bold"><i class="fa fa-handshake-o me-2"></i>Start a Meeting</h5>
                <small style="opacity:.85;">Log the client details before the meeting begins</small>
            </div>
            <form method="POST" action="{{ route('new-movement.start-meeting.process') }}" class="sm-form-body">
                @csrf
                <input type="hidden" name="latitude"  id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="sm-label">Entity Type</label>
                        <select name="entity_type" class="sm-input">
                            <option value="company">Company</option>
                            <option value="individual">Individual</option>
                            <option value="government">Government</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="sm-label">Meeting Type</label>
                        <select name="meeting_type" class="sm-input">
                            <option value="sales">Sales</option>
                            <option value="service">Service</option>
                            <option value="collection">Collection</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="sm-label">Client / Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="client_name" class="sm-input" required placeholder="e.g. Acme Corporation">
                    </div>
                    <div class="col-md-6">
                        <label class="sm-label">Contact Person</label>
                        <input type="text" name="contact_person" class="sm-input" placeholder="Full name">
                    </div>
                    <div class="col-md-6">
                        <label class="sm-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="sm-input" placeholder="01XXXXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="sm-label">Email</label>
                        <input type="email" name="contact_email" class="sm-input" placeholder="Optional">
                    </div>
                    <div class="col-md-6">
                        <label class="sm-label">Job Title</label>
                        <input type="text" name="contact_job_title" class="sm-input" placeholder="Manager, Director…">
                    </div>
                    <div class="col-12">
                        <label class="sm-label">Meeting Location</label>
                        <input type="text" name="location" id="location" class="sm-input" placeholder="Address or coordinates">
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <button type="button" class="gps-pill" onclick="getLocation()"><i class="fa fa-crosshairs me-1"></i>Use GPS</button>
                            <small class="text-muted" id="gps-status"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="sm-label">Remarks</label>
                        <textarea name="remarks" class="sm-input" rows="2" placeholder="Purpose of visit, additional notes…"></textarea>
                    </div>
                </div>

                <button type="submit" class="sm-btn-purple mt-4"><i class="fa fa-play me-2"></i>Start Meeting</button>
            </form>
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
            const loc = document.getElementById('location');
            if (!loc.value) loc.value = pos.coords.latitude.toFixed(5) + ', ' + pos.coords.longitude.toFixed(5);
            status.innerHTML = '<span style="color:#11998e;">✓ ' + pos.coords.latitude.toFixed(4) + ', ' + pos.coords.longitude.toFixed(4) + '</span>';
        }, function() { status.innerHTML = '<span style="color:#dc3545;">✗ Could not get location</span>'; });
    }
}
getLocation();
</script>
@endsection
