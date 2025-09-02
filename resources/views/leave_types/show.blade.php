@extends('layouts.default')

@section('title')
Leave Type Details @parent
@stop

@section('content')
<section class="content-header">
    <h1>Leave Type Details</h1>
</section>

<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Leave Type Details Fields -->
                <div class="col-md-12">
                    <p><b>Name:</b> {{ $leaveType->name }}</p>
                    <p><b>Total Days Per Year:</b> {{ $leaveType->total_days_per_year }}</p>
                    <p><b>Gender Criteria:</b> {{ $leaveType->gender_criteria ?? 'All' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('leaveTypes.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>
@endsection