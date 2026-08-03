@extends('layouts.default')

@section('title', 'Employee PF Accounts')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">Employee Provident Fund Accounts</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Employee ID</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>PF Account Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->name }} {{ $employee->last_name }}</td>
                                    <td>{{ $employee->emp_id }}</td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->designation->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($employee->pf_account_number)
                                            <span class="badge bg-success">{{ $employee->pf_account_number }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Not Generated</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$employee->pf_account_number)
                                            <form action="{{ route('pf.employees.generate_account', $employee->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Generate Account</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Generated</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable().destroy();
        }
        $('#datatable').DataTable();
    });
</script>
@endsection
