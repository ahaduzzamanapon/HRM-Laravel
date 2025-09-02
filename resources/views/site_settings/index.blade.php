@extends('layouts.default')

@section('title')
Site Settings @parent
@stop

@section('content')
<section class="content-header">
    <h1>Site Settings</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-body">
            @include('site_settings.table')
        </div>
    </div>
</div>
@endsection