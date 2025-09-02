@extends('layouts.default')

@section('title')
Create Leave Type @parent
@stop

@section('content')
<section class="content-header">
    <h1>Create Leave Type</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::open(['route' => 'leaveTypes.store']) !!}
        <div class="card-body">
            <div class="row">
                @include('leave_types.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('leaveTypes.index') }}" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection