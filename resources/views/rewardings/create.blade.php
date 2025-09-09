@extends('layouts.default')

{{-- Page title --}}
@section('title')
Rewarding @parent
@stop

@section('content')
    <section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>{{ __('Create New') }} Rewarding</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route' => 'rewardings.store', 'files' => true,'class' => 'form-horizontal col-md-12']) !!}
                    <div class="row">
                        @include('rewardings.fields')
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
