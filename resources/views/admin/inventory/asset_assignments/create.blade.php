@extends('layouts.default')

@section('title')
Assign Asset @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Assign Asset</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('adminlte-templates::common.errors')

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.inventory.asset-assignments.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" class="form-control" required>
                            <option value="">Select Available Asset</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->asset_code }} - {{ $asset->name }} ({{ $asset->brand }})</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Only assets with "Available" status are shown.</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Employee <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Assigned Date <span class="text-danger">*</span></label>
                        <input type="date" name="assigned_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Expected Return Date</label>
                        <input type="date" name="expected_return_date" class="form-control">
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Condition on Assignment</label>
                        <input type="text" name="condition_on_assignment" class="form-control" placeholder="e.g. Brand New, Slightly Used, Good">
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Any additional details..."></textarea>
                    </div>
                </div>

                <div class="card-footer px-0 pb-0 bg-white text-right">
                    <button type="submit" class="btn btn-primary">Assign Asset</button>
                    <a href="{{ route('admin.inventory.asset-assignments.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
