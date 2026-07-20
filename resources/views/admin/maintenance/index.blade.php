@extends('layouts.default')

@section('title')
Maintenance Dashboard @parent
@stop

@section('content')
<!-- Include FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<style>
    .fc-event {
        cursor: pointer;
        padding: 2px 5px;
        font-size: 0.85em;
        border-radius: 4px;
    }
    .metric-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 10px;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
    }
    .progress-bar-custom {
        height: 8px;
        border-radius: 4px;
    }
    .alert-panel {
        border-left: 4px solid;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header & Quick Actions -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800"><i class="fa fa-wrench text-primary"></i> Maintenance Management Dashboard</h1>
            <p class="text-muted mb-0">Overview of schedules, requests, costs, and assets maintenance status.</p>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group">
                <a href="{{ route('admin.maintenance.requests.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fa fa-plus-circle"></i> Create Request
                </a>
                <a href="{{ route('admin.maintenance.vendors.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fa fa-users"></i> Vendors
                </a>
                <a href="{{ route('admin.maintenance.types.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fa fa-cogs"></i> Types
                </a>
                <a href="{{ route('admin.maintenance.reports.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fa fa-file-text-o"></i> Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Active Alerts Section (due today / warranty expiring) -->
    @if($dueAlerts->count() > 0 || $expiringAssetWarranties->count() > 0 || $expiringMaintenanceWarranties->count() > 0 || $overdueRequests->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning-light border-0 py-3">
                        <h5 class="m-0 text-warning-dark font-weight-bold"><i class="fa fa-bell"></i> Attention Required & Active Alerts</h5>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            <!-- Overdue Alerts -->
                            @if($overdueRequests->count() > 0)
                                <div class="col-md-4 mb-2">
                                    <div class="alert alert-danger alert-panel mb-0 shadow-xs" style="border-left-color: #dc3545;">
                                        <strong><i class="fa fa-exclamation-triangle"></i> {{ $overdueRequests->count() }} Overdue Requests</strong>
                                        <div class="small mt-1 text-muted">Scheduled maintenance dates have passed without completion.</div>
                                        <a href="#overdue-section" class="alert-link small mt-1 d-inline-block">View Overdue List &rarr;</a>
                                    </div>
                                </div>
                            @endif

                            <!-- Due Alerts -->
                            @if($dueAlerts->count() > 0)
                                <div class="col-md-4 mb-2">
                                    <div class="alert alert-warning alert-panel mb-0 shadow-xs" style="border-left-color: #ffc107;">
                                        <strong><i class="fa fa-clock-o"></i> {{ $dueAlerts->count() }} Maintenance Due Today/Tomorrow</strong>
                                        <div class="small mt-1 text-muted">Prepare or assign tasks for items scheduled right now.</div>
                                        <a href="#due-section" class="alert-link small mt-1 d-inline-block">View Due List &rarr;</a>
                                    </div>
                                </div>
                            @endif

                            <!-- Warranty Expiry Alerts -->
                            @if($expiringAssetWarranties->count() > 0 || $expiringMaintenanceWarranties->count() > 0)
                                <div class="col-md-4 mb-2">
                                    <div class="alert alert-info alert-panel mb-0 shadow-xs" style="border-left-color: #17a2b8;">
                                        <strong><i class="fa fa-shield"></i> Warranty Expiry Warning</strong>
                                        <div class="small mt-1 text-muted">
                                            {{ $expiringAssetWarranties->count() }} assets & {{ $expiringMaintenanceWarranties->count() }} repairs have warranties expiring within 30 days.
                                        </div>
                                        <a href="#warranty-section" class="alert-link small mt-1 d-inline-block">Check Warranty Details &rarr;</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Metrics Cards Row -->
    <div class="row mb-4">
        <!-- Total Cost -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm metric-card" style="border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Maintenance Cost</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($totalCost, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-money fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm metric-card" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['Pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-hourglass-start fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm metric-card" style="border-left: 4px solid #17a2b8;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['In Progress'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-cogs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm metric-card" style="border-left: 4px solid #007bff;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Completed Tasks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['Completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="row">
        <!-- Left Column: Calendar & Due Lists -->
        <div class="col-xl-8 col-lg-7">
            <!-- Calendar Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-calendar"></i> Maintenance Schedule Calendar</h6>
                    <span class="badge badge-secondary">Visual Timeline</span>
                </div>
                <div class="card-body">
                    <div id="maintenanceCalendar"></div>
                </div>
            </div>

            <!-- Overdue Detection Section -->
            <div class="card border-0 shadow-sm mb-4" id="overdue-section">
                <div class="card-header bg-danger-light py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-warning"></i> Overdue Maintenance Detection</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Type</th>
                                    <th>Scheduled Date</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overdueRequests as $req)
                                    <tr>
                                        <td>
                                            <strong>{{ $req->asset ? $req->asset->name : 'N/A' }}</strong><br>
                                            <small class="text-muted">Code: {{ $req->asset ? $req->asset->asset_code : 'N/A' }}</small>
                                        </td>
                                        <td>{{ $req->type ? $req->type->name : 'N/A' }}</td>
                                        <td class="text-danger font-weight-bold">
                                            <i class="fa fa-calendar-times-o"></i> {{ $req->scheduled_date }}
                                        </td>
                                        <td>
                                            @if($req->priority == 'Critical' || $req->priority == 'High')
                                                <span class="badge badge-danger">{{ $req->priority }}</span>
                                            @elseif($req->priority == 'Medium')
                                                <span class="badge badge-warning">{{ $req->priority }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $req->priority }}</span>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-danger">{{ $req->status }}</span></td>
                                        <td>
                                            <a href="{{ route('admin.maintenance.requests.edit', $req->id) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-edit"></i> Handle
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fa fa-check-circle text-success fa-2x"></i><br>
                                            <span class="d-block mt-2">Awesome! No overdue maintenance requests detected.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Due Tracking Section -->
            <div class="card border-0 shadow-sm mb-4" id="due-section">
                <div class="card-header bg-warning-light py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-warning-dark"><i class="fa fa-calendar-check-o"></i> Due Tracking (Next 7 Days)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Type</th>
                                    <th>Scheduled Date</th>
                                    <th>Priority</th>
                                    <th>Vendor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dueRequests as $req)
                                    <tr>
                                        <td>
                                            <strong>{{ $req->asset ? $req->asset->name : 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $req->type ? $req->type->name : 'N/A' }}</td>
                                        <td class="font-weight-bold text-warning-dark">
                                            <i class="fa fa-clock-o"></i> {{ $req->scheduled_date }}
                                        </td>
                                        <td>
                                            @if($req->priority == 'Critical' || $req->priority == 'High')
                                                <span class="badge badge-danger">{{ $req->priority }}</span>
                                            @elseif($req->priority == 'Medium')
                                                <span class="badge badge-warning">{{ $req->priority }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $req->priority }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $req->vendor ? $req->vendor->name : 'Not Assigned' }}</td>
                                        <td>
                                            <a href="{{ route('admin.maintenance.requests.edit', $req->id) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No maintenance requests scheduled for the next 7 days.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Warranty Expiry Alerts Section -->
            <div class="card border-0 shadow-sm mb-4" id="warranty-section">
                <div class="card-header bg-info-light py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-shield"></i> Warranty Expiry Tracking (Next 30 Days)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Item Details</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Expiring Asset Warranties -->
                                @foreach($expiringAssetWarranties as $asset)
                                    @php
                                        $daysLeft = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($asset->warranty_expiry_date), false);
                                    @endphp
                                    <tr class="table-info">
                                        <td><span class="badge badge-info">Asset</span></td>
                                        <td>
                                            <strong>{{ $asset->name }}</strong><br>
                                            <small class="text-muted">Serial: {{ $asset->serial_number }}</small>
                                        </td>
                                        <td>{{ $asset->warranty_expiry_date }}</td>
                                        <td>{{ $daysLeft }} days left</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                    </tr>
                                @endforeach

                                <!-- Expiring Maintenance Warranties -->
                                @foreach($expiringMaintenanceWarranties as $req)
                                    @php
                                        $daysLeft = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($req->warranty_expiry_date), false);
                                    @endphp
                                    <tr class="table-warning">
                                        <td><span class="badge badge-warning">Repair</span></td>
                                        <td>
                                            <strong>{{ $req->title }}</strong> (Asset: {{ $req->asset ? $req->asset->name : 'N/A' }})<br>
                                            <small class="text-muted">Vendor: {{ $req->vendor ? $req->vendor->name : 'N/A' }}</small>
                                        </td>
                                        <td>{{ $req->warranty_expiry_date }}</td>
                                        <td>{{ $daysLeft }} days left</td>
                                        <td><span class="badge badge-secondary">{{ $req->status }}</span></td>
                                    </tr>
                                @endforeach

                                @if($expiringAssetWarranties->isEmpty() && $expiringMaintenanceWarranties->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No warranties expiring in the next 30 days.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Costs & History -->
        <div class="col-xl-4 col-lg-5">
            <!-- Vendor Cost Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-money"></i> Vendor Cost Summary</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($vendorCosts as $vCost)
                            @php
                                $percent = $totalCost > 0 ? ($vCost->total_cost / $totalCost) * 100 : 0;
                            @endphp
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <strong>{{ $vCost->vendor ? $vCost->vendor->name : 'N/A' }}</strong>
                                        <div class="progress progress-bar-custom mt-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-right">
                                        <span class="font-weight-bold text-dark">৳{{ number_format($vCost->total_cost, 2) }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">No cost records found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Asset Cost Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-bar-chart"></i> Asset-wise Cost Summary</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($assetCosts as $aCost)
                            @php
                                $percent = $totalCost > 0 ? ($aCost->total_cost / $totalCost) * 100 : 0;
                            @endphp
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <strong>{{ $aCost->asset ? $aCost->asset->name : 'N/A' }}</strong>
                                        <div class="progress progress-bar-custom mt-1" style="height: 5px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-right">
                                        <span class="font-weight-bold text-dark">৳{{ number_format($aCost->total_cost, 2) }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">No cost records found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Recent History -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-history"></i> Recent Requests History</h6>
                    <a href="{{ route('admin.maintenance.reports.index') }}" class="small">View All</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentRequests as $req)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-dark font-weight-bold">{{ $req->title }}</h6>
                                        <small class="text-muted">
                                            Asset: {{ $req->asset ? $req->asset->name : 'N/A' }} | Date: {{ $req->requested_date }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($req->status == 'Completed')
                                            <span class="badge badge-success">{{ $req->status }}</span>
                                        @elseif($req->status == 'Cancelled')
                                            <span class="badge badge-secondary">{{ $req->status }}</span>
                                        @else
                                            <span class="badge badge-primary">{{ $req->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">No recent requests.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Include FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('maintenanceCalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            themeSystem: 'bootstrap',
            events: @json($calendarEvents),
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault(); // Don't follow direct link if we handle navigation
                }
            },
            eventDidMount: function(info) {
                // Add popover tooltip or title attribute
                if (info.event.extendedProps.description) {
                    info.el.setAttribute('title', info.event.extendedProps.description);
                }
            }
        });
        calendar.render();
    });
</script>
@endpush
@endsection
