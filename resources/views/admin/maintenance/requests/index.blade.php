@extends('layouts.default')

@section('title')
Maintenance Requests @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Maintenance Requests</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ route('admin.maintenance.requests.create') }}">
                    <i class="fa fa-plus"></i> Add New Request
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.maintenance.requests.index') }}" method="GET" class="row">
                <div class="form-group col-sm-4">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label>Asset</label>
                    <select name="asset_id" class="form-control">
                        <option value="">All</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-info mr-2"><i class="fa fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.maintenance.requests.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Asset</th>
                            <th>Type</th>
                            <th>Vendor</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td class="align-middle">{{ $req->id }}</td>
                            <td class="align-middle fw-bold">{{ $req->title }}</td>
                            <td class="align-middle">{{ $req->asset ? $req->asset->name : 'N/A' }}</td>
                            <td class="align-middle">{{ $req->type ? $req->type->name : 'N/A' }}</td>
                            <td class="align-middle">{{ $req->vendor ? $req->vendor->name : 'N/A' }}</td>
                            <td class="align-middle">{{ $req->priority }}</td>
                            <td class="align-middle">
                                @php
                                    $badge = 'secondary';
                                    if($req->status == 'Pending') $badge = 'warning';
                                    elseif($req->status == 'Assigned') $badge = 'info';
                                    elseif($req->status == 'In Progress') $badge = 'primary';
                                    elseif($req->status == 'Completed') $badge = 'success';
                                    elseif($req->status == 'Cancelled') $badge = 'danger';
                                @endphp
                                <span class="badge badge-{{ $badge }} px-3 py-2 rounded-pill">{{ $req->status }}</span>
                            </td>
                            <td class="align-middle">{{ $req->requested_date }}</td>
                            <td class="align-middle">{{ $req->cost ? '$'.$req->cost : 'N/A' }}</td>
                            <td class="align-middle">
                                <form action="{{ route('admin.maintenance.requests.destroy', $req->id) }}" method="POST">
                                    <div class='btn-group'>
                                        <a href="{{ route('admin.maintenance.requests.edit', [$req->id]) }}" class='btn btn-outline-primary btn-sm'>
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No requests found.
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
