@extends('layouts.default')

@section('title')
Edit Leave Application @parent
@stop

@section('content')
<section class="content-header">
    <h1>Edit Leave Application</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::model($leaveApplication, ['route' => ['leaveApplications.update', $leaveApplication->id], 'method' => 'patch']) !!}
        <div class="card-body">
            <div class="row">
                @include('leave_applications.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('leaveApplications.index') }}" class="btn btn-primary">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection