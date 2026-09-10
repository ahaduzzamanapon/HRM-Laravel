@extends('layouts.default')

{{-- Page title --}}
@section('title')
Users @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
</section>

<!-- Main content -->
<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card" width="88vw;">
        <section class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0 fw-bold">Users</h5>

                <!-- Branch Wise Filter -->
                <style>
                    #branchFilterForm .select2-container {
                        min-width: 240px !important;
                        width: 240px !important;
                    }
                </style>
                <form action="javascript:void(0);" method="POST" class="d-flex align-items-center m-0" id="branchFilterForm">
                    @csrf
                    <label for="branch_id_filter" class="form-label mb-0 me-2 text-muted fw-semibold small text-nowrap">
                        <i class="im im-icon-Building me-1"></i> Branch:
                    </label>
                    <div style="min-width: 240px; width: 240px;">
                        <select name="branch_id" id="branch_id_filter" class="form-select form-select-sm shadow-sm">
                            <option value="">All Branches</option>
                            @foreach($branches as $id => $branchName)
                                <option value="{{ $id }}" {{ (string)$selectedBranchId === (string)$id ? 'selected' : '' }}>
                                    {{ $branchName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="btn_reset_branch_filter" class="btn btn-sm btn-outline-secondary ms-2 d-inline-flex align-items-center gap-1 text-nowrap" style="{{ empty($selectedBranchId) ? 'display:none !important;' : '' }}" title="Reset Filter">
                        <i class="im im-icon-Close fs-6" style="line-height: 1;"></i>
                        <span>Reset</span>
                    </button>
                </form>
            </div>

            @if(can('add_employee'))
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importEmployeesModal">
                    <i class="fa fa-upload me-1"></i> Import CSV
                </button>
                <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}">Add New</a>
            </div>
            @endif
        </section>
        <div class="card-body table-responsive" id="users-table-wrapper" style="transition: opacity 0.2s ease;">
            @include('users.table')
        </div>
    </div>
</div>

<!-- Import Employees Modal -->
<div class="modal fade" id="importEmployeesModal" tabindex="-1" aria-labelledby="importEmployeesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importEmployeesModalLabel">Import Employees from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label fw-bold">Select CSV File</label>
                        <input type="file" class="form-control" id="csvFile" name="file" accept=".csv" required>
                        <div class="form-text text-muted mt-2">
                            <strong>Column notes:</strong><br>
                            &bull; <code>designation_id</code>, <code>department_id</code>, <code>branch_id</code> accept <strong>names</strong> (e.g. <em>Officer</em>, <em>Finance</em>, <em>Head Office</em>). They will be created automatically if they don't exist.<br>
                            &bull; <code>basic_salary</code> — non-numeric values (e.g. <em>#N/A</em>) are treated as 0.<br>
                            &bull; Default password for imported employees: <code>12345678</code>
                        </div>
                    </div>
                    <a href="{{ route('users.sample') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-download"></i> Download Sample CSV
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('users.partials.transfer_modal')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#branch_id_filter').on('change', function() {
            const branchId = $(this).val();
            const $wrapper = $('#users-table-wrapper');

            if (branchId) {
                $('#btn_reset_branch_filter').css('display', 'inline-flex');
            } else {
                $('#btn_reset_branch_filter').css('display', 'none');
            }

            // Smooth opacity transition overlay while loading
            $wrapper.css('opacity', '0.4');

            $.ajax({
                url: "{{ route('users.filter') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    branch_id: branchId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $wrapper.html(response.html);

                        // Re-initialize DataTables with pagination, search, and export buttons
                        if ($.fn.DataTable.isDataTable('#users-table')) {
                            $('#users-table').DataTable().destroy();
                        }
                        $('#users-table').DataTable({
                            columnDefs: [
                                { orderable: false, targets: -1 }
                            ],
                            dom: 'Bfrtip',
                            buttons: [
                                { extend: 'copy', exportOptions: { columns: ':not(:last-child)' } },
                                { extend: 'csv', exportOptions: { columns: ':not(:last-child)' } },
                                { extend: 'excel', exportOptions: { columns: ':not(:last-child)' } },
                                { extend: 'pdf', exportOptions: { columns: ':not(:last-child)' } },
                                { extend: 'print', exportOptions: { columns: ':not(:last-child)' } }
                            ]
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Failed to filter users:', xhr);
                },
                complete: function() {
                    $wrapper.css('opacity', '1');
                }
            });
        });

        $('#btn_reset_branch_filter').on('click', function() {
            $('#branch_id_filter').val('').trigger('change');
        });
    });
</script>
@endpush
