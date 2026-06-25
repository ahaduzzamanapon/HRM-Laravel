@extends('layouts.default')

{{-- Page title --}}
@section('title')
Job Post @parent
@stop

@section('content')
   <section class="content-header">
    </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="card">
           <div class="card-body">
                <div class="row">
                    {!! Form::model($jobPost, ['route' => ['admin.jobs.update', $jobPost->id], 'method' => 'patch','class' => 'form-horizontal col-md-12']) !!}
                        <div class="row">
                            @include('admin.recruitment.jobs.fields')
                        </div>
                    {!! Form::close() !!}
                </div>
           </div>
       </div>
   </div>
@endsection
