@extends('layouts.default')

{{-- Page title --}}
@section('title')
Job Posts @parent
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
            <h5 class="card-title d-inline">Job Posts</h5>
            <span class="float-right">
                <a class="btn btn-primary pull-right" href="{{ route('admin.jobs.create') }}">Add New</a>
            </span>
        </section>
        <div class="card-body table-responsive">
            <table class="table table-striped table-bordered text-center" id="jobPosts-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->location }}</td>
                        <td>
                            @if($job->status == 'published')
                                <span class="badge bg-success">Published</span>
                            @elseif($job->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @else
                                <span class="badge bg-danger">Closed</span>
                            @endif
                        </td>
                        <td>{{ $job->deadline ? $job->deadline->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            <div class='btn-group'>
                                <a href="{{ route('admin.jobs.edit', [$job->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Pen"></i></a>
                                {!! Form::open(['route' => ['admin.jobs.destroy', $job->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="im im-icon-Remove"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center">
        @include('adminlte-templates::common.paginate', ['records' => $jobs])
    </div>
</div>
@endsection
