@extends(request('layout') == 'print' ? 'layouts.print' : 'layouts.default')

@section('title')
Maintenance Reports @parent
@stop

@section('content')
@if(request('layout') != 'print')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Maintenance Reports & Cost Summary</h3>
            </div>
        </div>
    </div>
</section>
@endif

<div class="content px-3">
    @if(request('layout') != 'print')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.maintenance.reports.index') }}" method="POST" class="row" target="report_window" onsubmit="window.open('', 'report_window', 'width=1200,height=800,scrollbars=yes,resizable=yes');">
                @csrf
                <input type="hidden" name="layout" value="print">
                <div class="form-group col-sm-3">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group col-sm-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="form-group col-sm-3">
                    <label>Asset</label>
                    <select name="asset_id" class="form-control">
                        <option value="">All Assets</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label>Vendor</label>
                    <select name="vendor_id" class="form-control">
                        <option value="">All Vendors</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="form-group col-sm-9 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-info mr-2"><i class="fa fa-filter"></i> Generate Report</button>
                    <a href="{{ route('admin.maintenance.reports.export', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger mr-2"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                    <a href="{{ route('admin.maintenance.reports.export', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success mr-2"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                    <a href="{{ route('admin.maintenance.reports.index') }}" class="btn btn-secondary">Reset</a>
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
            <a href="{{ route('admin.maintenance.reports.export', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('admin.maintenance.reports.export', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            <button onclick="window.print();" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Print</button>
            <button onclick="window.close();" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i> Close</button>
        </div>
        <div class="text-center mb-4 border-bottom pb-3">
            <div class="mb-2">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="Logo" style="height: 60px; object-fit: contain;">
                @else
                    <i class="fa fa-university fa-3x text-primary"></i>
                @endif
            </div>
            <h2 class="h4 mb-1 font-weight-bold">{{ $siteName }}</h2>
            @if($siteAddress)
                <div class="text-muted small mb-1"><i class="fa fa-map-marker"></i> {{ $siteAddress }}</div>
            @endif
            <div class="text-muted small"><strong>Maintenance Report</strong> | Generated on: {{ date('Y-m-d H:i') }}</div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Asset</th>
                        <th>Type</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td class="align-middle">{{ $req->requested_date }}</td>
                        <td class="align-middle">{{ $req->asset ? $req->asset->name : 'N/A' }}</td>
                        <td class="align-middle">{{ $req->type ? $req->type->name : 'N/A' }}</td>
                        <td class="align-middle">{{ $req->vendor ? $req->vendor->name : 'N/A' }}</td>
                        <td class="align-middle">
                            <span class="badge badge-secondary">{{ $req->status }}</span>
                        </td>
                        <td class="align-middle">৳{{ number_format($req->cost, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No records found for the selected criteria.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h3 class="card-title"><i class="fa fa-list"></i> Maintenance History</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Asset</th>
                                <th>Type</th>
                                <th>Vendor</th>
                                <th>Status</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td class="align-middle">{{ $req->requested_date }}</td>
                                <td class="align-middle">{{ $req->asset ? $req->asset->name : 'N/A' }}</td>
                                <td class="align-middle">{{ $req->type ? $req->type->name : 'N/A' }}</td>
                                <td class="align-middle">{{ $req->vendor ? $req->vendor->name : 'N/A' }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-secondary">{{ $req->status }}</span>
                                </td>
                                <td class="align-middle">৳{{ number_format($req->cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No records found for the selected criteria.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
