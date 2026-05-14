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
        <section class="card-header">
            <h5 class="card-title d-inline">Users</h5>
            <span class="float-right">
                <button type="button" class="btn btn-success mr-1" data-bs-toggle="modal" data-bs-target="#importEmployeesModal">
                    <i class="fa fa-upload"></i> Import CSV
                </button>
                <a class="btn btn-primary" href="{{ route('users.create') }}">Add New</a>
            </span>
        </section>
        <div class="card-body table-responsive">
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
