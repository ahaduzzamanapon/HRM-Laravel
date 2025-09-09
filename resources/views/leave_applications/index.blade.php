@extends('layouts.default')

@section('title')
Leave Applications @parent
@stop

@section('content')
<section class="content-header">
    <h1>Leave Applications</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Leave Applications</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('leaveApplications.create') }}">Apply for Leave</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Half Day</th>
                            <th>Requested Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Approved At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveApplications as $application)
                            <tr>
                                <td>{{ $application->user->name ?? 'N/A' }}</td>
                                <td>{{ $application->leaveType->name ?? 'N/A' }}</td>
                                <td>{{ $application->start_date->format('Y-m-d') }}</td>
                                <td>{{ $application->end_date->format('Y-m-d') }}</td>
                                <td>{{ $application->is_half_day ? 'Yes' : 'No' }}</td>
                                <td>{{ $application->requested_days }}</td>
                                <td>{{ $application->reason }}</td>
                                <td>{{ $application->status }}</td>
                                <td>{{ $application->approver->name ?? 'N/A' }}</td>
                                <td>{{ $application->approved_at ? $application->approved_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href="{{ route('leaveApplications.show', [$application->id]) }}" class='btn btn-primary btn-xs'>View</a>
                                        @if($application->status === 'Pending')
                                            <a href="{{ route('leaveApplications.edit', [$application->id]) }}" class='btn btn-primary btn-xs'>Edit</a>
                                            {!! Form::open(['route' => ['leaveApplications.destroy', $application->id], 'method' => 'delete', 'class' => 'd-inline']) !!}
                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this leave application?')">Delete</button>
                                            {!! Form::close() !!}
                                            @if(Auth::check() && Auth::user()->can('approve_leave'))
                                                <a href="{{ route('leaveApplications.approve', [$application->id]) }}" class='btn btn-success btn-xs' onclick="return confirm('Are you sure you want to approve this leave application?')">Approve</a>
                                                <a href="{{ route('leaveApplications.reject', [$application->id]) }}" class='btn btn-warning btn-xs' onclick="return confirm('Are you sure you want to reject this leave application?')">Reject</a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center">
                {!! $leaveApplications->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection