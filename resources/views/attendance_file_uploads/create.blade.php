@extends('layouts.default')

@section('title')
Upload Attendance File @parent
@stop

@section('content')
<section class="content-header">
    <h1>Upload Attendance File</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::open(['route' => 'attendanceFileUploads.store', 'files' => true]) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('attendance_file', 'Select Attendance File (TXT):') !!}
                        {!! Form::file('attendance_file', ['class' => 'form-control', 'accept' => '.txt']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Upload and Process', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('attendanceFileUploads.index') }}" class="btn btn-primary">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection