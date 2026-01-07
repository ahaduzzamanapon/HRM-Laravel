@extends('layouts.default')

{{-- Page title --}}
@section('title')
User @parent
@stop

@section('content')
    <section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>{{ __('Create New') }} User</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
<div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-file-excel-o"></i> Import Employees
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Choose CSV/Excel File</label>
                                <input type="file" name="file" class="form-control" required accept=".csv, .xlsx, .xls">
                            </div>
                            <button type="submit" class="btn btn-success">Import</button>
                            <a href="{{ route('users.sample') }}" class="btn btn-info"><i class="fa fa-download"></i> Download Sample</a>
                        </form>
                    </div>
                     <div class="col-md-6">
                        <p class="text-muted">
                            <strong>Instructions:</strong><br>
                            1. Download the sample file.<br>
                            2. Fill in the employee details.<br>
                            3. Use valid IDs for Designation, Department, etc.<br>
                            4. Upload the file to create employees in bulk.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route' => 'users.store', 'files' => true,'class' => 'form-horizontal col-md-12']) !!}
                    <div class="row">
                        @include('users.fields')
                    </div>


                    <button type="submit" class="btn btn-primary">Create User</button>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var d = new Date();
            var emp_id = $('#emp_id').val()
            if (emp_id=='') {
                $('#emp_id').val('EMP-'+d.getTime());
            }
        });
    </script>
@endpush
