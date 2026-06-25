@extends('layouts.default')

{{-- Page title --}}
@section('title')
Applications @parent
@stop

@section('content')
<section class="content-header">
</section>

<div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>

    <div class="card" width="88vw;">
        <section class="card-header">
            <h5 class="card-title d-inline">Applications</h5>
        </section>
        
        <div class="card-body">
            <!-- Filter Form -->
            {!! Form::open(['route' => 'admin.applications.index', 'method' => 'GET', 'class' => 'form-inline mb-4']) !!}
                <div class="form-group mr-2">
                    {!! Form::select('recruitment_id', $jobPosts->prepend('All Jobs', ''), request('recruitment_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group mr-2">
                    {!! Form::select('status', ['' => 'All Statuses', 'new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected'], request('status'), ['class' => 'form-control']) !!}
                </div>
                {!! Form::submit('Filter', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.applications.index') }}" class="btn btn-default ml-2">Clear</a>
            {!! Form::close() !!}
            <br>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center" id="applications-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Job Post</th>
                            <th>Email & Phone</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                            <td>{{ optional($application->recruitment)->title ?? 'N/A' }}</td>
                            <td>
                                {{ $application->email }}<br>
                                <small>{{ $application->phone }}</small>
                            </td>
                            <td>
                                @if($application->status == 'new')
                                    <span class="badge bg-info">New</span>
                                @elseif($application->status == 'reviewed')
                                    <span class="badge bg-primary">Reviewed</span>
                                @elseif($application->status == 'shortlisted')
                                    <span class="badge bg-success">Shortlisted</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.applications.download', $application->id) }}" class="btn btn-sm btn-outline-info" target="_blank">View CV</a>
                            </td>
                            <td>
                                {!! Form::open(['route' => ['admin.applications.update-status', $application->id], 'method' => 'patch']) !!}
                                    <div class="input-group input-group-sm">
                                        {!! Form::select('status', ['new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected'], $application->status, ['class' => 'form-control', 'required']) !!}
                                        <div class="input-group-append">
                                            {!! Form::button('Update', ['type' => 'submit', 'class' => 'btn btn-outline-primary btn-sm']) !!}
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        @include('adminlte-templates::common.paginate', ['records' => $applications])
    </div>
</div>
@endsection
