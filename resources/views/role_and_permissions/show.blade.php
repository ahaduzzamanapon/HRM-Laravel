@extends('layouts.default')

{{-- Page title --}}
@section('title')
Role Details: {{ $roleAndPermission->name }} @parent
@stop

@section('content')
<div class="content pt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="im im-icon-Shield me-2 text-primary"></i> Role Details: {{ $roleAndPermission->name }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('roleAndPermissions.index') }}">Role Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Role Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('roleAndPermissions.edit', [$roleAndPermission->id]) }}" class="btn btn-primary">
                <i class="im im-icon-Pen me-1"></i> Edit Role
            </a>
            <a href="{{ route('roleAndPermissions.index') }}" class="btn btn-outline-secondary">
                <i class="im im-icon-Arrow-Left me-1"></i> Back to List
            </a>
        </div>
    </div>

    @include('flash::message')

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @include('role_and_permissions.show_fields')
        </div>
    </div>
</div>
@endsection
