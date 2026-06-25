@extends('layouts.default')

@section('title')
Asset Categories @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Asset Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ route('admin.inventory.asset-categories.create') }}">
                    <i class="fa fa-plus"></i> Add New Category
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

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="align-middle">{{ $category->id }}</td>
                            <td class="align-middle fw-bold">{{ $category->name }}</td>
                            <td class="align-middle">
                                @if($category->status)
                                    <span class="badge badge-success px-3 py-2 rounded-pill">Active</span>
                                @else
                                    <span class="badge badge-danger px-3 py-2 rounded-pill">Inactive</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <form action="{{ route('admin.inventory.asset-categories.destroy', $category->id) }}" method="POST">
                                    <div class='btn-group'>
                                        <a href="{{ route('admin.inventory.asset-categories.edit', [$category->id]) }}" class='btn btn-outline-primary btn-sm'>
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
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No categories found.
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
