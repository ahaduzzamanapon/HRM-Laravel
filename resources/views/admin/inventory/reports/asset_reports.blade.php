@extends('layouts.default')

@section('title')
Asset Reports @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Asset Reports</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.inventory.reports.assets', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                <a href="{{ route('admin.inventory.reports.assets', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success ml-2"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.reports.assets') }}" class="row mb-4">
                <div class="form-group col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Condition</label>
                    <select name="condition" class="form-control">
                        <option value="">All Conditions</option>
                        <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                        <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Department</label>
                    {!! Form::select('department_id', ['' => 'All Departments'] + $data['departments']->toArray(), request('department_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-3">
                    <label>Purchase Date (From)</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Purchase Date (To)</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="form-group col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.inventory.reports.assets') }}" class="btn btn-default ml-2">Clear</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Asset Name</th>
                            <th>Category</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Condition</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>{{ $asset->asset_tag }}</td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ optional($asset->category)->name }}</td>
                                <td>{{ optional($asset->department)->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($asset->status) }}</span></td>
                                <td>{{ ucfirst($asset->condition) }}</td>
                                <td>{{ $asset->location }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No assets found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
