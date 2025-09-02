@extends('layouts.default')

@section('title')
Leave Application Details @parent
@stop

@section('content')
<section class="content-header">
    <h1>Leave Application Details</h1>
</section>

<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Leave Application Details Fields -->
                <div class="col-md-12">
                    <p><b>Employee:</b> {{ $leaveApplication->user->name ?? 'N/A' }}</p>
                    <p><b>Leave Type:</b> {{ $leaveApplication->leaveType->name ?? 'N/A' }}</p>
                    <p><b>Start Date:</b> {{ $leaveApplication->start_date->format('Y-m-d') }}</p>
                    <p><b>End Date:</b> {{ $leaveApplication->end_date->format('Y-m-d') }}</p>
                    <p><b>Half Day:</b> {{ $leaveApplication->is_half_day ? 'Yes' : 'No' }}</p>
                    <p><b>Requested Days:</b> {{ $leaveApplication->requested_days }}</p>
                    <p><b>Reason:</b> {{ $leaveApplication->reason }}</p>
                    <p><b>Status:</b> {{ $leaveApplication->status }}</p>
                    <p><b>Approved By:</b> {{ $leaveApplication->approver->name ?? 'N/A' }}</p>
                    <p><b>Approved At:</b> {{ $leaveApplication->approved_at ? $leaveApplication->approved_at->format('Y-m-d H:i') : 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('leaveApplications.index') }}" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
@endsection