@extends('layouts.default')

@section('title')
Return Asset @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Return Asset</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('adminlte-templates::common.errors')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h3 class="card-title">
                <i class="fa fa-undo"></i> Return {{ $assignment->asset->name }} ({{ $assignment->asset->asset_code }})<br>
                <small class="text-muted">Assigned to: {{ $assignment->employee->name ?? '' }} {{ $assignment->employee->last_name ?? '' }}</small>
            </h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Assigned Date:</strong> {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}</p>
                    <p><strong>Expected Return:</strong> {{ $assignment->expected_return_date ? \Carbon\Carbon::parse($assignment->expected_return_date)->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Condition on Assignment:</strong> {{ $assignment->condition_on_assignment ?: 'N/A' }}</p>
                </div>
            </div>
            
            <hr>

            <form action="{{ route('admin.inventory.asset-assignments.processReturn', $assignment->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Return Date <span class="text-danger">*</span></label>
                        <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Condition on Return <span class="text-danger">*</span></label>
                        <input type="text" name="condition_on_return" class="form-control" placeholder="e.g. Good, Damaged, Needs Repair" required>
                    </div>

                    <div class="col-md-12 form-group">
                        <label>New Asset Status <span class="text-danger">*</span></label>
                        <select name="asset_status" class="form-control" required>
                            <option value="Available">Available (Ready for re-assignment)</option>
                            <option value="Maintenance">Maintenance (Needs repair/checking)</option>
                            <option value="Retired">Retired (No longer in use)</option>
                            <option value="Disposed">Disposed (Thrown away/Recycled)</option>
                        </select>
                        <small class="form-text text-muted">This will automatically update the asset's overall status.</small>
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Return Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Any issues or details regarding the return..."></textarea>
                    </div>
                </div>

                <div class="card-footer px-0 pb-0 bg-white text-right">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-undo"></i> Process Return</button>
                    <a href="{{ route('admin.inventory.asset-assignments.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
