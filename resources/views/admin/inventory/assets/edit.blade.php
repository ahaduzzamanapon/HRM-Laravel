@extends('layouts.default')

@section('title')
Edit Asset @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>Edit Asset</h1>
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
            <form action="{{ route('admin.inventory.assets.update', $asset->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="asset_code">Asset Code <span class="text-danger">*</span></label>
                        <input type="text" name="asset_code" id="asset_code" class="form-control" value="{{ old('asset_code', $asset->asset_code) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="name">Asset Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $asset->name) }}" required>
                    </div>
                    <div class="col-md-6 form-group mt-3">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mt-3">
                        <label for="department_id">Department</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $asset->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mt-3">
                        <label for="brand">Brand</label>
                        <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand', $asset->brand) }}">
                    </div>
                    <div class="col-md-6 form-group mt-3">
                        <label for="model">Model</label>
                        <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $asset->model) }}">
                    </div>
                    <div class="col-md-6 form-group mt-3">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number', $asset->serial_number) }}">
                    </div>
                    <div class="col-md-6 form-group mt-3">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Available" {{ old('status', $asset->status) == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Assigned" {{ old('status', $asset->status) == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="Maintenance" {{ old('status', $asset->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Retired" {{ old('status', $asset->status) == 'Retired' ? 'selected' : '' }}>Retired</option>
                            <option value="Disposed" {{ old('status', $asset->status) == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group mt-3">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date', $asset->purchase_date) }}">
                    </div>
                    <div class="col-md-4 form-group mt-3">
                        <label for="purchase_cost">Purchase Cost</label>
                        <input type="number" step="0.01" name="purchase_cost" id="purchase_cost" class="form-control" value="{{ old('purchase_cost', $asset->purchase_cost) }}">
                    </div>
                    <div class="col-md-4 form-group mt-3">
                        <label for="warranty_expiry_date">Warranty Expiry Date</label>
                        <input type="date" name="warranty_expiry_date" id="warranty_expiry_date" class="form-control" value="{{ old('warranty_expiry_date', $asset->warranty_expiry_date) }}">
                    </div>
                    <div class="col-md-12 form-group mt-3">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $asset->location) }}">
                    </div>
                    <div class="col-md-12 form-group mt-3">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-control">{{ old('notes', $asset->notes) }}</textarea>
                    </div>
                </div>

                <div class="form-group mt-4 text-right">
                    <a href="{{ route('admin.inventory.assets.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
