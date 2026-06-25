@extends('layouts.default')

@section('title')
Maintenance Reports @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Maintenance Reports & Cost Summary</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.maintenance.reports.index') }}" method="GET" class="row">
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

    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-light shadow-sm">
                <span class="info-box-icon bg-success"><i class="fa fa-money-bill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Maintenance Cost</span>
                    <span class="info-box-number h3">${{ number_format($totalCost, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-light shadow-sm">
                <span class="info-box-icon bg-info"><i class="fa fa-wrench"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Requests</span>
                    <span class="info-box-number h3">{{ $requests->count() }}</span>
                </div>
            </div>
        </div>
    </div>

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
                            <td class="align-middle">${{ number_format($req->cost, 2) }}</td>
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
</div>
@endsection
