@extends('layouts.default')

@section('title')
Edit Training Detail @parent
@stop

@section('content')
<section class="content-header">
    <h1>Edit Training Detail</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::model($trainingDetail, ['route' => ['trainingDetails.update', $trainingDetail->id], 'method' => 'patch']) !!}
        <div class="card-body">
            <div class="row">
                                @include('training_details._fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('trainingDetails.index') }}" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection