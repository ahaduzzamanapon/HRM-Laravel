@extends('layouts.default')

{{-- Page title --}}
@section('title')
System Permission Keys @parent
@stop

@section('content')
<div class="content pt-3">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>

    <div class="card shadow-sm border-0">
        <section class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title m-0 fw-bold text-primary">
                <i class="im im-icon-Key me-2"></i> System Permission Keys Registry
            </h5>
            <a class="btn btn-primary btn-sm shadow-sm" href="{{ route('permissions.create') }}">
                <i class="im im-icon-Add me-1"></i> Add Permission Key
            </a>
        </section>
        <div class="card-body p-0 table-responsive">
            @include('permissions.table')
        </div>
        <div class="card-footer bg-white border-0 py-2 d-flex justify-content-end">
            {{ $permissions->links() }}
        </div>
    </div>
</div>
@endsection
