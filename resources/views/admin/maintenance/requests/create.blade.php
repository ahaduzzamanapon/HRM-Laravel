@extends('layouts.default')

@section('title')
Add Maintenance Request @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Maintenance Request</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-secondary" href="{{ route('admin.maintenance.requests.index') }}">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
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
            <form action="{{ route('admin.maintenance.requests.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="asset_id">Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" id="asset_id" class="form-control" required>
                            <option value="">Select Asset</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>{{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="type_id">Maintenance Type <span class="text-danger">*</span></label>
                        <select name="type_id" id="type_id" class="form-control" required>
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-control">
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="priority">Priority <span class="text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Assigned" {{ old('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="requested_date">Requested Date <span class="text-danger">*</span></label>
                        <input type="date" name="requested_date" id="requested_date" class="form-control" required value="{{ old('requested_date', date('Y-m-d')) }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="scheduled_date">Scheduled Date</label>
                        <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" value="{{ old('scheduled_date') }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="completion_date">Completion Date</label>
                        <input type="date" name="completion_date" id="completion_date" class="form-control" value="{{ old('completion_date') }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="cost">Cost</label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-control" value="{{ old('cost') }}">
                    </div>
                    
                    <div class="form-group col-sm-4">
                        <label for="warranty_expiry_date">Warranty Expiry Date</label>
                        <input type="date" name="warranty_expiry_date" id="warranty_expiry_date" class="form-control" value="{{ old('warranty_expiry_date') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="form-group col-sm-12">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <div class="card-footer px-0 bg-transparent text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Request</button>
                    <a href="{{ route('admin.maintenance.requests.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
