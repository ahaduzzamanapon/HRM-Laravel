@extends('layouts.default')

@section('title')
Assets Management @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Assets Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ route('admin.inventory.assets.create') }}">
                    <i class="fa fa-plus"></i> Add New Asset
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
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="fa fa-filter"></i> Filter Assets</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.inventory.assets.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Retired" {{ request('status') == 'Retired' ? 'selected' : '' }}>Retired</option>
                            <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center" id="assets-table">
                    <thead class="bg-light">
                        <tr>
                            <th>Asset Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($assets as $asset)
                        <tr>
                            <td class="align-middle fw-bold">{{ $asset->asset_code }}</td>
                            <td class="align-middle">{{ $asset->name }}</td>
                            <td class="align-middle">{{ $asset->category->name ?? 'N/A' }}</td>
                            <td class="align-middle">
                                @if($asset->status == 'Available')
                                    <span class="badge badge-success px-3 py-2 rounded-pill">Available</span>
                                @elseif($asset->status == 'Assigned')
                                    <span class="badge badge-info px-3 py-2 rounded-pill">Assigned</span>
                                @elseif($asset->status == 'Maintenance')
                                    <span class="badge badge-warning px-3 py-2 rounded-pill">Maintenance</span>
                                @elseif($asset->status == 'Retired')
                                    <span class="badge badge-secondary px-3 py-2 rounded-pill">Retired</span>
                                @else
                                    <span class="badge badge-danger px-3 py-2 rounded-pill">Disposed</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $asset->department->name ?? 'N/A' }}</td>
                            <td class="align-middle">
                                <form action="{{ route('admin.inventory.assets.destroy', $asset->id) }}" method="POST">
                                    <div class='btn-group'>
                                        <a href="{{ route('admin.inventory.assets.show', [$asset->id]) }}" class='btn btn-outline-info btn-sm'>
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.inventory.assets.edit', [$asset->id]) }}" class='btn btn-outline-primary btn-sm'>
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No assets found.
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
