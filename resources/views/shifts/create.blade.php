@extends('layouts.default')

@section('title')
Create Shift @parent
@stop

@section('content')
<section class="content-header">
    <h1>Create Shift</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::open(['route' => 'shifts.store']) !!}
        <div class="card-body">
            <div class="row">
                @include('shifts.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('shifts.index') }}" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection