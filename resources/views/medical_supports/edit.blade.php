@extends('layouts.default')

{{-- Page title --}}
@section('title')
Medical Support @parent
@stop

@section('content')
   <section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>{{ __('Edit') }} Medical Support</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
    </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="card">
           <div class="card-body">
                <div class="row">
                    {!! Form::model($medicalSupport, ['route' => ['medicalSupports.update', $medicalSupport->id], 'method' => 'patch', 'files' => true,'class' => 'form-horizontal col-md-12']) !!}
                        <div class="row">
                            @include('medical_supports.fields')
                        </div>
                    {!! Form::close() !!}
                </div>
           </div>
       </div>
   </div>
@endsection
