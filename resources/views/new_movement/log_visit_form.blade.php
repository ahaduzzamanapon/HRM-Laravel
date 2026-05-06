@extends('layouts.default')
@section('title', 'Log Client Visit')
@section('content')
<div class="container py-4" style="max-width:640px;">
    <h4 class="fw-bold mb-4"><i class="fa fa-eye me-2 text-primary"></i>Log Client Visit</h4>
    <form method="POST" action="{{ route('new-movement.log-visit.process') }}">
        @csrf
        <input type="hidden" name="latitude"  id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <div class="card shadow-sm border-0 p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Entity Type</label>
                    <select name="entity_type" class="form-select">
                        <option value="company">Company</option>
                        <option value="individual">Individual</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Client / Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Location</label>
                    <input type="text" name="location" id="location" class="form-control">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="getLocation()"><i class="fa fa-crosshairs me-1"></i>Use GPS</button>
                    <small class="text-muted d-block mt-1" id="gps-status"></small>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Why did the visit not lead to a meeting?"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4"><i class="fa fa-save me-2"></i>Save Visit Log</button>
        </div>
    </form>
</div>
<script>
function getLocation() {
    document.getElementById('gps-status').textContent = 'Getting location…';
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
            if (!document.getElementById('location').value)
                document.getElementById('location').value = pos.coords.latitude.toFixed(5) + ', ' + pos.coords.longitude.toFixed(5);
            document.getElementById('gps-status').textContent = '✓ ' + pos.coords.latitude.toFixed(4) + ', ' + pos.coords.longitude.toFixed(4);
        }, function() { document.getElementById('gps-status').textContent = '✗ Could not get location'; });
    }
}
getLocation();
</script>
@endsection
