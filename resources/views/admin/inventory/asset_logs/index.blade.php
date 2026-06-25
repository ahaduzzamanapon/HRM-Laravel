@extends('layouts.default')

@section('title')
Asset Audit Logs @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Asset Audit Logs</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-2" data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" style="cursor: pointer;">
            <h5 class="mb-0 font-weight-normal" style="font-size: 1rem;"><i class="fa fa-filter text-muted mr-2"></i> Filter Logs</h5>
            <i class="fa fa-chevron-down text-muted"></i>
        </div>
        <div class="collapse" id="filterCollapse">
            <div class="card-body py-2">
                <form id="filterForm" action="{{ route('admin.inventory.asset-logs.index') }}" method="POST">
                    @csrf
                    <input type="hidden" name="page" id="pageInput" value="{{ request('page', 1) }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="mb-1" style="font-size: 0.9em;">Asset</label>
                                <select name="asset_id" class="form-control form-control-sm">
                                    <option value="">All Assets</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->asset_code }} - {{ $asset->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="mb-1" style="font-size: 0.9em;">Event Type</label>
                                <select name="event_type" class="form-control form-control-sm">
                                    <option value="">All Events</option>
                                    <option value="Registered" {{ request('event_type') == 'Registered' ? 'selected' : '' }}>Registered</option>
                                    <option value="Updated" {{ request('event_type') == 'Updated' ? 'selected' : '' }}>Updated</option>
                                    <option value="Assigned" {{ request('event_type') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="Returned" {{ request('event_type') == 'Returned' ? 'selected' : '' }}>Returned</option>
                                    <option value="Maintenance" {{ request('event_type') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="Audit" {{ request('event_type') == 'Audit' ? 'selected' : '' }}>Audit</option>
                                    <option value="Note" {{ request('event_type') == 'Note' ? 'selected' : '' }}>Note</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="mb-1" style="font-size: 0.9em;">Employee</label>
                                <select name="user_id" class="form-control form-control-sm">
                                    <option value="">All Employees</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="mb-1" style="font-size: 0.9em;">From Date</label>
                                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="mb-1" style="font-size: 0.9em;">To Date</label>
                                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-secondary btn-sm" onclick="document.getElementById('pageInput').value=1;"><i class="fa fa-search"></i> Filter</button>
                                <a href="{{ route('admin.inventory.asset-logs.index') }}" class="btn btn-outline-secondary btn-sm ml-1"><i class="fa fa-sync"></i> Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('flash::message')

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Asset</th>
                            <th>Event Type</th>
                            <th>Status Change</th>
                            <th>Related User / Dept</th>
                            <th>Action By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.inventory.assets.show', $log->asset_id) }}">
                                        {{ $log->asset->name ?? 'N/A' }}
                                    </a><br>
                                    <small class="text-muted">{{ $log->asset->asset_code ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($log->event_type == 'Registered') badge-primary
                                        @elseif($log->event_type == 'Assigned') badge-info
                                        @elseif($log->event_type == 'Returned') badge-success
                                        @elseif($log->event_type == 'Maintenance') badge-warning
                                        @else badge-secondary @endif
                                    ">
                                        {{ $log->event_type }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->old_status || $log->new_status)
                                        <small>{{ $log->old_status ?? 'None' }} &rarr; {{ $log->new_status ?? 'None' }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($log->employee)
                                        <i class="fa fa-user text-muted"></i> {{ $log->employee->first_name }} {{ $log->employee->last_name }}<br>
                                    @endif
                                    @if($log->department)
                                        <i class="fa fa-building text-muted"></i> <small>{{ $log->department->name }}</small>
                                    @endif
                                </td>
                                <td>{{ $log->actionBy->first_name ?? 'System' }} {{ $log->actionBy->last_name ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No audit logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer bg-white border-top pagination-wrapper">
                {{ $logs->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Intercept pagination links to submit via POST form
    const paginationLinks = document.querySelectorAll('.pagination-wrapper a.page-link');
    
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Extract page number from URL
            const url = new URL(this.href);
            const page = url.searchParams.get('page');
            
            if (page) {
                document.getElementById('pageInput').value = page;
                document.getElementById('filterForm').submit();
            }
        });
    });
});
</script>
@endsection
