@extends('layouts.default')

@section('title')
Apply for Leave @parent
@stop

@section('content')
<section class="content-header">
    <h1>Apply for Leave</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::open(['route' => 'leaveApplications.store']) !!}
        <div class="card-body">
            <div class="row">
                @include('leave_applications.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Apply', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('leaveApplications.index') }}" class="btn btn-primary">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection