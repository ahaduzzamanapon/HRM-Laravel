@extends('layouts.default')

{{-- Page title --}}
@section('title')
Role And Permissions @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>Role And Permission</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="table-responsive">
        <table class="table table-default">
            @include('role_and_permissions.show_fields')

            </table>
        </div>
    </div>
    <a href="{{ route('roleAndPermissions.index') }}"
                class="btn btn-primary">Back</a>
</div>
@endsection
