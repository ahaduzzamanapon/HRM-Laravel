@extends(request('layout') == 'print' ? 'layouts.print' : 'layouts.default')

@section('title')
General Inventory Reports @parent
@stop

@section('content')
@if(request('layout') != 'print')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>General Inventory Reports</h3>
            </div>
        </div>
    </div>
</section>
@endif

<div class="content px-3">
    @if(request('layout') != 'print')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.reports.inventory') }}" class="row mb-0" target="inventory_window" onsubmit="window.open('', 'inventory_window', 'width=1200,height=800,scrollbars=yes,resizable=yes');">
                @csrf
                <input type="hidden" name="layout" value="print">
                <div class="form-group col-md-4">
                    <label>Location</label>
                    {!! Form::select('location', ['' => 'All Locations'] + $data['locations']->toArray(), request('location'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-4">
                    <label>Department</label>
                    {!! Form::select('department_id', ['' => 'All Departments'] + $data['departments']->toArray(), request('department_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-4 d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" name="low_stock" value="1" class="custom-control-input" id="low_stock" {{ request('low_stock') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="low_stock">Show Unallocated (Low Stock Analysis)</label>
                    </div>
                </div>
                <div class="form-group col-md-12 text-right mb-0">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Generate Report</button>
                    <a href="{{ route('admin.inventory.reports.inventory', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger ml-2"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                    <a href="{{ route('admin.inventory.reports.inventory', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success ml-2"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                    <a href="{{ route('admin.inventory.reports.inventory') }}" class="btn btn-default ml-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    @endif

    @if(request('layout') == 'print')
        @php
            $siteSetting = \App\Models\SiteSetting::first();
            $siteName = $siteSetting->site_name ?? 'Corporate HRM';
            $siteLogo = $siteSetting->site_logo ? asset($siteSetting->site_logo) : null;
            $siteAddress = $siteSetting->site_address ?? '';
        @endphp
        <div class="text-right mb-3 no-print">
            <a href="{{ route('admin.inventory.reports.inventory', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('admin.inventory.reports.inventory', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            <button onclick="window.print();" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Print</button>
            <button onclick="window.close();" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i> Close</button>
        </div>
        <div class="text-center mb-4 border-bottom pb-3">
            <div class="mb-2">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="Logo" style="height: 60px;">
                @endif
            </div>
            <h2 class="mb-1" style="font-weight: bold; color: #333;">{{ $siteName }}</h2>
            @if($siteAddress)
                <p class="text-muted mb-2" style="font-size: 14px;">{{ $siteAddress }}</p>
            @endif
            <h4 class="mt-3 text-secondary font-weight-bold" style="letter-spacing: 0.5px;">General Inventory Report</h4>
            <small class="text-muted">Generated on: {{ date('Y-m-d H:i') }}</small>
        </div>
    @endif

    @if(request('layout') != 'print' || (request('layout') == 'print' && $inventory->count() > 0))
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Asset Name</th>
                            <th>Category</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory as $asset)
                            <tr>
                                <td>{{ $asset->asset_tag }}</td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ optional($asset->category)->name }}</td>
                                <td>{{ optional($asset->department)->name ?? 'N/A' }}</td>
                                <td>{{ $asset->location ?? 'Unspecified' }}</td>
                                <td>
                                    @if($asset->status == 'available')
                                        <span class="badge badge-success">In Stock</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($asset->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No inventory records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
