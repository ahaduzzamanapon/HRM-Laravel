@extends('layouts.default')

@section('title')
Disciplinary Case {{ $departmentalCase->case_no ?? ('#' . $departmentalCase->id) }} @parent
@stop

@section('content')
<div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>

    <!-- Header Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                        <i class="fa fa-shield fa-2x"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold mb-0 text-dark">Case Ref: {{ $departmentalCase->case_no ?? ('DC-' . str_pad($departmentalCase->id, 4, '0', STR_PAD_LEFT)) }}</h4>
                            <span class="badge {{ $departmentalCase->status_badge_class }} px-3 py-2 fs-6">
                                {{ $departmentalCase->status ?? 'Pending' }}
                            </span>
                        </div>
                        <p class="text-muted mb-0 small">
                            <i class="fa fa-user me-1"></i>Employee: <strong>{{ $departmentalCase->employee->name ?? 'N/A' }} {{ $departmentalCase->employee->last_name ?? '' }}</strong>
                            <span class="mx-2">|</span>
                            <i class="fa fa-building-o me-1"></i>Branch: <strong>{{ $departmentalCase->employee->branch->branch_name ?? 'Head Office' }}</strong>
                            <span class="mx-2">|</span>
                            <i class="fa fa-calendar me-1"></i>Registered: {{ $departmentalCase->created_at->format('d M, Y') }}
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print();">
                        <i class="fa fa-print me-1"></i> Print Case File
                    </button>

                    @if(!($isEmployeeOnly ?? false) && (can('notify_departmental_cases') || can('manage_departmental_cases')))
                        <form action="{{ route('departmentalCases.notify', [$departmentalCase->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Send official disciplinary notification email to employee?');">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm text-dark shadow-sm">
                                <i class="fa fa-paper-plane me-1"></i> Notify Employee
                            </button>
                        </form>
                    @endif

                    @if(!($isEmployeeOnly ?? false) && (can('edit_departmental_cases') || can('manage_departmental_cases')))
                        <a href="{{ route('departmentalCases.edit', [$departmentalCase->id]) }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="fa fa-pencil me-1"></i> Edit Case
                        </a>
                    @endif

                    <a href="{{ route('departmentalCases.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Details Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            @include('departmental_cases.show_fields')
        </div>
    </div>
</div>
@endsection
