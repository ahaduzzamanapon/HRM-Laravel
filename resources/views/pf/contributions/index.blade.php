@extends('layouts.default')

@section('title', 'PF Contributions')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">PF Contributions</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContributionModal">Add Contribution</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Scheme</th>
                                    <th>Date</th>
                                    <th>Employee Contribution</th>
                                    <th>Company Contribution</th>
                                    <th>Voluntary</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contributions as $index => $contribution)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contribution->employee->name ?? 'N/A' }} {{ $contribution->employee->last_name ?? '' }}</td>
                                    <td>{{ $contribution->scheme->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($contribution->contribution_date)->format('M d, Y') }}</td>
                                    <td>{{ number_format($contribution->employee_contribution, 2) }}</td>
                                    <td>{{ number_format($contribution->employer_contribution, 2) }}</td>
                                    <td>{{ number_format($contribution->voluntary_contribution, 2) }}</td>
                                    <td>
                                        @if($contribution->status == 'Pending')
                                            <span class="badge bg-warning text-dark">{{ $contribution->status }}</span>
                                        @elseif($contribution->status == 'Processed')
                                            <span class="badge bg-success">{{ $contribution->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $contribution->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contribution->status == 'Pending')
                                            <form action="{{ route('pf.contributions.process', $contribution->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Process</button>
                                            </form>
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

<!-- Add Contribution Modal -->
<div class="modal fade" id="addContributionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pf.contributions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Monthly Contribution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PF Scheme <span class="text-danger">*</span></label>
                        <select name="scheme_id" class="form-select" required>
                            <option value="">Select Scheme</option>
                            @foreach($schemes as $scheme)
                                <option value="{{ $scheme->id }}">{{ $scheme->name }} ({{ $scheme->employee_contribution_percentage }}%)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contribution Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="contribution_date" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee Contribution <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="employee_contribution" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Contribution <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="employer_contribution" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Voluntary Contribution</label>
                        <input type="number" step="0.01" class="form-control" name="voluntary_contribution" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Contribution</button>
                </div>
            </form>
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
