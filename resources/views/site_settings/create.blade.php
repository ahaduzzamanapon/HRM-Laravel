@extends('layouts.default')

@section('title')
Create Site Setting @parent
@stop

@section('content')
<section class="content-header">
    <h1>Create Site Setting</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::open(['route' => 'siteSettings.store', 'files' => true]) !!}
        <div class="card-body">
            <div class="row">
                @include('site_settings.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('siteSettings.index') }}" class="btn btn-primary">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection