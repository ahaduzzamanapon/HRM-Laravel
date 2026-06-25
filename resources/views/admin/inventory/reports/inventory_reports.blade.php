@extends('layouts.default')

@section('title')
General Inventory Reports @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>General Inventory Reports</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.inventory.reports.inventory', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                <a href="{{ route('admin.inventory.reports.inventory', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success ml-2"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.reports.inventory') }}" class="row mb-4">
                <div class="form-group col-md-4">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" placeholder="Search Location" value="{{ request('location') }}">
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
                <div class="form-group col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.inventory.reports.inventory') }}" class="btn btn-default ml-2">Clear</a>
                </div>
            </form>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info"><i class="fa fa-boxes"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Assets</span>
                            <span class="info-box-number">{{ $stats['total'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success"><i class="fa fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Available (In Stock)</span>
                            <span class="info-box-number">{{ $stats['available'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning"><i class="fa fa-user-tag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Allocated</span>
                            <span class="info-box-number">{{ $stats['allocated'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-danger"><i class="fa fa-tools"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">In Maintenance</span>
                            <span class="info-box-number">{{ $stats['maintenance'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

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
</div>
@endsection
