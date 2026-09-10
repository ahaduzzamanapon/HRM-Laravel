@extends('layouts.default')

@section('title')
Disciplinary Action & Departmental Cases @parent
@stop

@section('content')
<div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>

    <!-- Executive Summary Stat Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold small">Total Disciplinary Cases</span>
                            <h3 class="fw-bold mb-0 text-primary mt-1">{{ number_format($totalCases ?? 0) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                            <i class="fa fa-balance-scale fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold small">Pending & Investigation</span>
                            <h3 class="fw-bold mb-0 text-info mt-1">{{ number_format($pendingCases ?? 0) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                            <i class="fa fa-search fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-warning">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold small">Show Cause & Hearing</span>
                            <h3 class="fw-bold mb-0 text-warning mt-1">{{ number_format($showCauseCases ?? 0) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                            <i class="fa fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-danger">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold small">Penalty Imposed & Closed</span>
                            <h3 class="fw-bold mb-0 text-danger mt-1">{{ number_format($penalizedCases ?? 0) }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                            <i class="fa fa-gavel fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title fw-bold text-secondary mb-0">
                <i class="fa fa-shield me-2 text-primary"></i>{{ !empty($isEmployeeOnly) ? 'My Disciplinary Cases & Penalties' : 'Disciplinary Cases Directory' }}
            </h5>
            @if(can('add_departmental_cases') || can('manage_departmental_cases'))
            <div>
                <a class="btn btn-primary btn-sm rounded-2 shadow-sm" href="{{ route('departmentalCases.create') }}">
                    <i class="fa fa-plus me-1"></i> Register New Case
                </a>
            </div>
            @endif
        </div>

        <!-- Filter Bar -->
        <div class="card-body bg-light bg-opacity-50 border-bottom py-3">
            <form method="GET" action="{{ route('departmentalCases.index') }}" class="row g-2 align-items-center">
                @if(isSuperAdmin())
                <div class="col-md-3">
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Branches</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Case Statuses</option>
                        @foreach($statuses as $stVal => $stLabel)
                            <option value="{{ $stVal }}" {{ request('status') == $stVal ? 'selected' : '' }}>{{ $stLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Search case ref, employee, allegation..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-2 text-end">
                    <a href="{{ route('departmentalCases.index') }}" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fa fa-refresh me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="card-body p-0">
            @include('departmental_cases.table')
        </div>

        @if(method_exists($departmentalCases, 'links'))
        <div class="card-footer bg-white py-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small text-muted">
                    Showing {{ $departmentalCases->firstItem() ?? 0 }} to {{ $departmentalCases->lastItem() ?? 0 }} of {{ $departmentalCases->total() ?? 0 }} records
                </span>
                <div>
                    {{ $departmentalCases->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
