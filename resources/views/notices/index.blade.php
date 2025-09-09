@extends('layouts.default')

{{-- Page title --}}
@section('title')
Notices @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>Notices</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
</section>

<!-- Main content -->
<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card" width="88vw;">
        <section class="card-header">
            <h5 class="card-title d-inline">Notices</h5>
            @if (can('add_notices'))
            <span class="float-right">
                <a class="btn btn-primary pull-right" href="{{ route('notices.create') }}">Add New</a>
            </span>
            @endif
        </section>
        <div class="card-body table-responsive" >
            @include('notices.table')
        </div>
    </div>
    <div class="text-center">
        
        @include('adminlte-templates::common.paginate', ['records' => $notices])

    </div>
</div>
@endsection
