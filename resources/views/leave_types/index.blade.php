@extends('layouts.default')

@section('title')
Leave Types @parent
@stop

@section('content')
<section class="content-header">
    <h1>Leave Types</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Leave Types</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('leaveTypes.create') }}">Add New Leave Type</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Total Days Per Year</th>
                            <th>Gender Criteria</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTypes as $leaveType)
                            <tr>
                                <td>{{ $leaveType->name }}</td>
                                <td>{{ $leaveType->total_days_per_year }}</td>
                                <td>{{ $leaveType->gender_criteria ?? 'All' }}</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href="{{ route('leaveTypes.show', [$leaveType->id]) }}" class='btn btn-primary btn-xs'>View</a>
                                        <a href="{{ route('leaveTypes.edit', [$leaveType->id]) }}" class='btn btn-primary btn-xs'>Edit</a>
                                        {!! Form::open(['route' => ['leaveTypes.destroy', $leaveType->id], 'method' => 'delete', 'class' => 'd-inline']) !!}
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this leave type?')">Delete</button>
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center">
                {!! $leaveTypes->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection