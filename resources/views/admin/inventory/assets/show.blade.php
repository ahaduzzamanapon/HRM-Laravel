@extends('layouts.default')

@section('title')
Asset Details @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Asset Details</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-secondary" href="{{ route('admin.inventory.assets.index') }}">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
                <a class="btn btn-primary" href="{{ route('admin.inventory.assets.edit', $asset->id) }}">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h3 class="card-title">{{ $asset->name }} ({{ $asset->asset_code }})</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 35%">Asset Code</th>
                                <td>{{ $asset->asset_code }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $asset->name }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $asset->category->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($asset->status == 'Available')
                                        <span class="badge badge-success px-2 py-1">Available</span>
                                    @elseif($asset->status == 'Assigned')
                                        <span class="badge badge-info px-2 py-1">Assigned</span>
                                    @elseif($asset->status == 'Maintenance')
                                        <span class="badge badge-warning px-2 py-1">Maintenance</span>
                                    @elseif($asset->status == 'Retired')
                                        <span class="badge badge-secondary px-2 py-1">Retired</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1">Disposed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $asset->department->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $asset->location ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 35%">Brand</th>
                                <td>{{ $asset->brand ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $asset->model ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Serial Number</th>
                                <td>{{ $asset->serial_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Date</th>
                                <td>{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Cost</th>
                                <td>{{ $asset->purchase_cost ? number_format($asset->purchase_cost, 2) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Warranty Expiry</th>
                                <td>{{ $asset->warranty_expiry_date ? \Carbon\Carbon::parse($asset->warranty_expiry_date)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-3">
                    <strong>Notes:</strong>
                    <div class="p-3 bg-light border rounded">
                        {{ $asset->notes ?: 'No notes available.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content px-3 mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title">Lifecycle Timeline</h3>
            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addLogModal">
                <i class="fa fa-plus"></i> Add Manual Log
            </button>
        </div>
        <div class="card-body">
            @if($logs->count() > 0)
                <div class="timeline">
                    @foreach($logs as $log)
                        <div>
                            <i class="fa 
                                @if($log->event_type == 'Registered') fa-star bg-primary
                                @elseif($log->event_type == 'Assigned') fa-user-check bg-info
                                @elseif($log->event_type == 'Returned') fa-undo bg-success
                                @elseif($log->event_type == 'Updated') fa-edit bg-secondary
                                @elseif($log->event_type == 'Note') fa-comment bg-warning
                                @else fa-circle bg-dark @endif
                            "></i>
                            <div class="timeline-item shadow-sm">
                                <span class="time"><i class="fa fa-clock"></i> {{ $log->created_at->format('M d, Y h:i A') }}</span>
                                <h3 class="timeline-header font-weight-bold">
                                    {{ $log->event_type }}
                                    @if($log->actionBy)
                                        <small class="text-muted ml-2">by {{ $log->actionBy->first_name }} {{ $log->actionBy->last_name }}</small>
                                    @endif
                                </h3>
                                <div class="timeline-body">
                                    @if($log->old_status || $log->new_status)
                                        <div class="mb-2">
                                            <strong>Status:</strong> 
                                            @if($log->old_status) <span class="badge badge-light border">{{ $log->old_status }}</span> <i class="fa fa-arrow-right mx-1 text-muted" style="font-size: 0.8em;"></i> @endif
                                            <span class="badge badge-primary">{{ $log->new_status }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($log->employee)
                                        <div class="mb-2"><strong>Employee:</strong> {{ $log->employee->first_name }} {{ $log->employee->last_name }}</div>
                                    @endif
                                    
                                    @if($log->notes)
                                        <div class="p-2 bg-light rounded text-muted mt-2 border-left border-info" style="border-left-width: 3px !important;">
                                            {!! nl2br(e($log->notes)) !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <i class="fa fa-clock bg-gray"></i>
                    </div>
                </div>
            @else
                <p class="text-muted text-center py-4">No timeline events found for this asset.</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Log Modal -->
<div class="modal fade" id="addLogModal" tabindex="-1" role="dialog" aria-labelledby="addLogModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.inventory.assets.addLog', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addLogModalLabel">Add Manual Log Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Event Type <span class="text-danger">*</span></label>
                        <select name="event_type" class="form-control" required>
                            <option value="Note">General Note</option>
                            <option value="Maintenance">Maintenance Record</option>
                            <option value="Audit">Audit Check</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Update Status (Optional)</label>
                        <select name="new_status" class="form-control">
                            <option value="">-- Do Not Change Status --</option>
                            <option value="Available" {{ $asset->status == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Maintenance" {{ $asset->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Retired" {{ $asset->status == 'Retired' ? 'selected' : '' }}>Retired</option>
                            <option value="Disposed" {{ $asset->status == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                        <small class="text-muted">Selecting a new status will automatically update the asset's current status.</small>
                    </div>
                    <div class="form-group">
                        <label>Notes <span class="text-danger">*</span></label>
                        <textarea name="notes" class="form-control" rows="4" required placeholder="Enter details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    margin: 0 0 30px 0;
    padding: 0;
    position: relative;
}
.timeline::before {
    border-radius: 2px;
    background: #e9ecef;
    bottom: 0;
    content: '';
    left: 31px;
    margin: 0;
    position: absolute;
    top: 0;
    width: 4px;
}
.timeline > div {
    margin-bottom: 15px;
    margin-right: 10px;
    position: relative;
}
.timeline > div::before, .timeline > div::after {
    content: "";
    display: table;
}
.timeline > div > .fa {
    background: #adb5bd;
    border-radius: 50%;
    color: #fff;
    font-size: 15px;
    height: 30px;
    left: 18px;
    line-height: 30px;
    position: absolute;
    text-align: center;
    top: 0;
    width: 30px;
}
.timeline > div > .timeline-item {
    box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
    border-radius: 4px;
    background: #fff;
    color: #495057;
    margin-left: 60px;
    margin-right: 15px;
    margin-top: 0;
    padding: 0;
    position: relative;
}
.timeline > div > .timeline-item > .time {
    color: #999;
    float: right;
    font-size: 12px;
    padding: 10px;
}
.timeline > div > .timeline-item > .timeline-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
    color: #495057;
    font-size: 16px;
    line-height: 1.1;
    margin: 0;
    padding: 10px;
}
.timeline > div > .timeline-item > .timeline-body {
    padding: 10px;
}
</style>
@endsection
