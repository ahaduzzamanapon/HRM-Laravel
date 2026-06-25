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
                    {!! Form::open(['route' => 'admin.jobs.store', 'class' => 'form-horizontal col-md-12']) !!}
                        <div class="row">
                            @include('admin.recruitment.jobs.fields')
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
