@extends('layouts.default')

@section('title')
Medical Supports @parent
@stop

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 text-dark fw-bold">Medical Supports</h2>
        </div>
        <div class="col-auto">
            <a class="btn btn-primary rounded-pill px-4 shadow-sm" href="{{ route('medicalSupports.create') }}">
                <i class="im im-icon-Add me-1"></i> Add New Application
            </a>
        </div>
    </div>

    @include('flash::message')

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title fw-bold text-dark mb-0">
                <i class="im im-icon-File-TXT text-primary me-2"></i>Medical Support Applications
            </h5>
        </div>
        <div class="card-body p-0 table-responsive">
            @include('medical_supports.table')
        </div>
    </div>

    <div class="text-center mt-3">
        @include('adminlte-templates::common.paginate', ['records' => $medicalSupports])
    </div>
</div>
@endsection
