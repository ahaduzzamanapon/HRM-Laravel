@extends('layouts.default')

@section('title')
Training Details @parent
@stop

@section('content')
<section class="content-header">
    <h1>Training Details</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-body">
            {{-- Training Details Table --}}
        </div>
    </div>
</div>
@endsection