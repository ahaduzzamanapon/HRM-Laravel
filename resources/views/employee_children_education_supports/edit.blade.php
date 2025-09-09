@extends('layouts.default')

{{-- Page title --}}
@section('title')
Employee Children Education Support @parent
@stop

@section('content')
   <section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>{{ __('Edit') }} Employee Children Education Support</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
    </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="card">
           <div class="card-body">
                <div class="row">
                    {!! Form::model($employeeChildrenEducationSupport, ['route' => ['employeeChildrenEducationSupports.update', $employeeChildrenEducationSupport->id], 'method' => 'patch', 'files' => true,'class' => 'form-horizontal col-md-12']) !!}
                        <div class="row">
                            @include('employee_children_education_supports.fields')
                        </div>
                    {!! Form::close() !!}
                </div>
           </div>
       </div>
   </div>
@endsection
