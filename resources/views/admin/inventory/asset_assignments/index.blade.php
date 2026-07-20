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
                                <option value="Assigned">Active Assignments</option>
                                <option value="Returned">Returned History</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee</label>
                            <select name="user_id" class="form-control">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->name }} {{ $emp->last_name }}
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
        <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
            <ul class="nav nav-tabs border-0" id="assignmentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold text-white" id="grouped-tab" data-toggle="tab" href="#grouped" role="tab" aria-controls="grouped" aria-selected="true">
                        <i class="fa fa-users mr-1"></i> By Employee
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-white" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">
                        <i class="fa fa-list mr-1"></i> All Assignments
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="assignmentTabsContent">
                
                <!-- Grouped by Employee Tab -->
                <div class="tab-pane fade show active" id="grouped" role="tabpanel" aria-labelledby="grouped-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Total Devices (Assigned/Returned)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groupedAssignments as $userId => $userAssignments)
                                    @php
                                        $employee = $userAssignments->first()->employee;
                                        $activeCount = $userAssignments->where('status', 'Assigned')->count();
                                        $returnedCount = $userAssignments->where('status', 'Returned')->count();
                                    @endphp
                                    <tr>
                                        <td class="align-middle fw-bold text-left pl-4">
                                            {{ $employee->name ?? 'Unknown' }} {{ $employee->last_name ?? '' }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-info px-2 py-1">{{ $activeCount }} Active</span>
                                            <span class="badge badge-secondary px-2 py-1">{{ $returnedCount }} Returned</span>
                                        </td>
                                        <td class="align-middle">
                                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#viewModal{{ $userId }}">
                                                <i class="fa fa-eye"></i> View Devices
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                            No assignments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Flat List Tab -->
                <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-center">
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
                                        <td class="text-left pl-3">
                                            <strong>{{ $assignment->asset->name }}</strong><br>
                                            <small class="text-muted">{{ $assignment->asset->asset_code }}</small>
                                        </td>
                                        <td class="align-middle">{{ $assignment->employee->name ?? 'Unknown' }} {{ $assignment->employee->last_name ?? '' }}</td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}</td>
                                        <td class="align-middle">
                                            @if($assignment->expected_return_date)
                                                @php
                                                    $isOverdue = $assignment->status == 'Assigned' && \Carbon\Carbon::parse($assignment->expected_return_date)->isPast();
                                                @endphp
                                                <span class="{{ $isOverdue ? 'text-danger font-weight-bold' : '' }}">
                                                    {{ \Carbon\Carbon::parse($assignment->expected_return_date)->format('M d, Y') }}
                                                    @if($isOverdue) <i class="fa fa-exclamation-circle" title="Overdue"></i> @endif
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $assignment->return_date ? \Carbon\Carbon::parse($assignment->return_date)->format('M d, Y') : '-' }}</td>
                                        <td class="align-middle">
                                            @if($assignment->status == 'Assigned')
                                                <span class="badge badge-info px-2 py-1">Assigned</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Returned</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($assignment->status == 'Assigned')
                                                <a href="{{ route('admin.inventory.asset-assignments.returnForm', $assignment->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-undo"></i> Return
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled>Returned</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                            No assignments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer clearfix bg-white">
            <div class="float-right">
                {{ $assignments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modals for each Employee -->
@foreach($groupedAssignments as $userId => $userAssignments)
    @php
        $employee = $userAssignments->first()->employee;
    @endphp
    <div class="modal fade" id="viewModal{{ $userId }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fa fa-box mr-2"></i> Assigned Devices: {{ $employee->name ?? 'Unknown' }} {{ $employee->last_name ?? '' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 text-center">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Assigned Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userAssignments as $assignment)
                                <tr>
                                    <td class="text-left pl-3">
                                        <strong>{{ $assignment->asset->name }}</strong><br>
                                        <small class="text-muted">{{ $assignment->asset->asset_code }}</small>
                                    </td>
                                    <td class="align-middle">{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}</td>
                                    <td class="align-middle">{{ $assignment->return_date ? \Carbon\Carbon::parse($assignment->return_date)->format('M d, Y') : '-' }}</td>
                                    <td class="align-middle">
                                        @if($assignment->status == 'Assigned')
                                            <span class="badge badge-info">Assigned</span>
                                        @else
                                            <span class="badge badge-secondary">Returned</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($assignment->status == 'Assigned')
                                            <a href="{{ route('admin.inventory.asset-assignments.returnForm', $assignment->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-undo"></i> Return
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>Returned</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
