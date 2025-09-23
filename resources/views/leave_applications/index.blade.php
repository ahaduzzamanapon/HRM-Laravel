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
                            <th>Date</th>
                            <th>Requested Days</th>
                            <th>Status</th>
                            <th>Current Approver</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveApplications as $application)
                            <tr>
                                <td>{{ $application->user->name ?? 'N/A' }}</td>
                                <td>{{ $application->leaveType->name ?? 'N/A' }}</td>
                                <td>{{ $application->start_date->format('Y-m-d') }} <br> to <br> {{ $application->end_date->format('Y-m-d') }}</td>
                                <td>{{ $application->requested_days }}</td>
                                <td>{{ $application->status }}</td>
                                <td>
                                    @if($application->status == 'First Level Approved')
                                        {{ $application->finalApprover->name ?? 'N/A' }}
                                    @else
                                        {{ $application->approver->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    <div class='btn-group'>
                                        <a href="{{ route('leaveApplications.show', [$application->id]) }}" class='btn btn-primary btn-xs'>View</a>
                                        @if($application->status === 'Pending')
                                            {!! Form::open(['route' => ['leaveApplications.first.approve', $application->id], 'method' => 'post', 'class' => 'd-inline'])
                                            !!}
                                            <button type="submit" class="btn btn-success btn-xs">First Approve</button>
                                            {!! Form::close() !!}
                                        @endif
                                        @if($application->status === 'First Level Approved')
                                            {!! Form::open(['route' => ['leaveApplications.final.approve', $application->id], 'method' => 'post', 'class' => 'd-inline'])
                                            !!}
                                            <button type="submit" class="btn btn-success btn-xs">Final Approve</button>
                                            {!! Form::close() !!}
                                        @endif
                                        @if($application->status !== 'Approved' && $application->status !== 'Rejected')
                                            {!! Form::open(['route' => ['leaveApplications.reject', $application->id], 'method' => 'post', 'class' => 'd-inline'])
                                            !!}
                                            <button type="submit" class="btn btn-danger btn-xs">Reject</button>
                                            {!! Form::close() !!}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {!! $leaveApplications->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
