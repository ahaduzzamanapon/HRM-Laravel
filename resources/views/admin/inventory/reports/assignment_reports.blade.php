@extends('layouts.default')

@section('title')
Assignment Reports @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Assignment Reports</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.inventory.reports.assignments', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                <a href="{{ route('admin.inventory.reports.assignments', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success ml-2"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.reports.assignments') }}" class="row mb-4">
                <div class="form-group col-md-3">
                    <label>Employee</label>
                    {!! Form::select('employee_id', ['' => 'All Employees'] + $data['employees']->toArray(), request('employee_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-3">
                    <label>Department</label>
                    {!! Form::select('department_id', ['' => 'All Departments'] + $data['departments']->toArray(), request('department_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-3">
                    <label>Branch</label>
                    {!! Form::select('branch_id', ['' => 'All Branches'] + $data['branches']->toArray(), request('branch_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-3">
                    <label>Assigned Date (From)</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Assigned Date (To)</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="form-group col-md-3 d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" name="is_overdue" value="1" class="custom-control-input" id="is_overdue" {{ request('is_overdue') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_overdue">Show Overdue Only</label>
                    </div>
                </div>
                <div class="form-group col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.inventory.reports.assignments') }}" class="btn btn-default ml-2">Clear</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Branch</th>
                            <th>Assigned Date</th>
                            <th>Expected Return</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            @php 
                                $isOverdue = $assignment->status == 'allocated' && $assignment->expected_return_date && \Carbon\Carbon::parse($assignment->expected_return_date)->isPast();
                            @endphp
                            <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                <td>{{ optional($assignment->asset)->name }}</td>
                                <td>{{ optional($assignment->employee)->name }}</td>
                                <td>{{ optional(optional($assignment->employee)->department)->name }}</td>
                                <td>{{ optional(optional($assignment->employee)->branch)->branch_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('Y-m-d') }}</td>
                                <td>{{ $assignment->expected_return_date ? \Carbon\Carbon::parse($assignment->expected_return_date)->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $assignment->status == 'returned' ? 'success' : 'warning' }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="badge badge-danger">OVERDUE</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No assignments found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
