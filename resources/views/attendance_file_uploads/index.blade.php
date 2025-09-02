@extends('layouts.default')

@section('title')
Attendance File Uploads @parent
@stop

@section('content')
<section class="content-header">
    <h1>Attendance File Uploads</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Uploaded Attendance Data</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('attendanceFileUploads.create') }}">Upload New File</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Punch ID</th>
                            <th>Date/Time</th>
                            <th>Device ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendanceData as $data)
                            <tr>
                                <td>{{ $data->punch_id }}</td>
                                <td>{{ $data->date_time }}</td>
                                <td>{{ $data->device_id }}</td>
                                <td>
                                    {{-- No edit/show for individual records, only delete --}}
                                    {!! Form::open(['route' => ['attendanceFileUploads.destroy', $data->id], 'method' => 'delete']) !!}
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">Delete</button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        {{-- Pagination Links --}}
            <div class="d-flex justify-content-center">
                {!! $attendanceData->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection