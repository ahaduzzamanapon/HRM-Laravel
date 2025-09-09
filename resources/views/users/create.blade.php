@extends('layouts.default')

{{-- Page title --}}
@section('title')
User @parent
@stop

@section('content')
    <section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>{{ __('Create New') }} User</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route' => 'users.store', 'files' => true,'class' => 'form-horizontal col-md-12']) !!}
                    <div class="row">
                        @include('users.fields')
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var d = new Date();
            var emp_id = $('#emp_id').val()
            if (emp_id=='') {
                $('#emp_id').val('EMP-'+d.getTime());
            }
        });
    </script>
@endpush
