@extends('layouts.default')

@section('title')
Asset Assignments @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Asset Assignments</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ route('admin.inventory.asset-assignments.create') }}">
                    <i class="fa fa-plus"></i> Assign Asset
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.inventory.asset-assignments.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Active Assignments</option>
                                <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>Returned History</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee</label>
                            <select name="user_id" class="form-control">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" style="margin-top: 28px;">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                            <a href="{{ route('admin.inventory.asset-assignments.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('flash::message')

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Asset</th>
                            <th>Employee</th>
                            <th>Assigned Date</th>
                            <th>Expected Return</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <strong>{{ $assignment->asset->name }}</strong><br>
                                    <small class="text-muted">{{ $assignment->asset->asset_code }}</small>
                                </td>
                                <td>{{ $assignment->employee->first_name ?? '' }} {{ $assignment->employee->last_name ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}</td>
                                <td>
                                    @if($assignment->expected_return_date)
                                        @php
                                            $isOverdue = $assignment->status == 'Assigned' && \Carbon\Carbon::parse($assignment->expected_return_date)->isPast();
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-danger font-weight-bold' : '' }}">
                                            {{ \Carbon\Carbon::parse($assignment->expected_return_date)->format('M d, Y') }}
                                            @if($isOverdue) <i class="fa fa-exclamation-circle" title="Overdue"></i> @endif
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $assignment->return_date ? \Carbon\Carbon::parse($assignment->return_date)->format('M d, Y') : '-' }}</td>
                                <td>
                                    @if($assignment->status == 'Assigned')
                                        <span class="badge badge-info px-2 py-1">Assigned</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">Returned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->status == 'Assigned')
                                        <a href="{{ route('admin.inventory.asset-assignments.returnForm', $assignment->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-undo"></i> Return Asset
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Returned</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No assignments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
