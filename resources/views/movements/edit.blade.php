@extends('layouts.default')

@section('title')
Edit Movement @parent
@stop

@section('content')
<section class="content-header">
    <h1>Edit Movement</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::model($movement, ['route' => ['movements.update', $movement->id], 'method' => 'patch']) !!}
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