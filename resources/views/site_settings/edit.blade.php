@extends('layouts.default')

@section('title')
Edit Site Setting @parent
@stop

@section('content')
<section class="content-header">
    <h1>Edit Site Setting</h1>
</section>

<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        {!! Form::model($siteSetting, ['route' => ['siteSettings.update', $siteSetting->id], 'method' => 'patch', 'files' => true]) !!}
        <div class="card-body">
            <div class="row">
                @include('site_settings.fields')
            </div>
        </div>
        <div class="card-footer">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('siteSettings.index') }}" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection