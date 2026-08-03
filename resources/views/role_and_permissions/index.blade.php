@extends('layouts.default')

{{-- Page title --}}
@section('title')
Role And Permissions @parent
@stop

@section('content')
<div class="content pt-3">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>

    <div class="card shadow-sm border-0">
        <section class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title m-0 fw-bold text-primary">
                <i class="im im-icon-Shield me-2"></i> Roles & Permissions List
            </h5>
            <div class="d-flex align-items-center gap-2 flex-wrap" style="max-width: 500px; width: 100%; justify-content: flex-end;">
                <div style="max-width: 280px; width: 100%;">
                    <input type="text" id="roleSearchInput" class="form-control form-control-sm" placeholder="Search roles...">
                </div>
                <a class="btn btn-primary btn-sm shadow-sm" href="{{ route('roleAndPermissions.create') }}">
                    <i class="im im-icon-Add me-1"></i> Add New Role
                </a>
            </div>
        </section>
        <div class="card-body p-0 table-responsive">
            @include('role_and_permissions.table')
        </div>
        <div class="card-footer bg-white border-0 py-2 d-flex justify-content-end">
            {{ $roleAndPermissions->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('roleSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const term = this.value.toLowerCase();
                const rows = document.querySelectorAll('#roleAndPermissions-table tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
@endsection
