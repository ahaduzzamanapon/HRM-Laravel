@extends('layouts.default')

@section('title')
Add Asset Category @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>Add Asset Category</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.inventory.asset-categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="form-group mt-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group mt-4 text-right">
                    <a href="{{ route('admin.inventory.asset-categories.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
