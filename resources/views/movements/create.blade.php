@extends('layouts.default')

@section('title')
Create Movement @parent
@stop

@section('content')
<section class="content-header">
    <h1>Create Movement</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::open(['route' => 'movements.store']) !!}
        <div class="card-body">
            <div class="row">
                @include('movements.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('movements.index') }}" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection