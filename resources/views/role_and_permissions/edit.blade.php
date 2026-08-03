@extends('layouts.default')

{{-- Page title --}}
@section('title')
Edit Role & Permission @parent
@stop

@section('content')
<div class="content pt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="im im-icon-Pen me-2 text-primary"></i> Edit Role: {{ $roleAndPermission->name }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('roleAndPermissions.index') }}">Role Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('roleAndPermissions.index') }}" class="btn btn-outline-secondary">
            <i class="im im-icon-Arrow-Left me-1"></i> Back to List
        </a>
    </div>

    @include('adminlte-templates::common.errors')

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            {!! Form::model($roleAndPermission, ['route' => ['roleAndPermissions.update', $roleAndPermission->id], 'method' => 'patch', 'class' => 'form-horizontal']) !!}
                @include('role_and_permissions.fields')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
