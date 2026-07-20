@extends('layouts.default')

@section('title')
Pension Policies @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-4">
                <h3 class="font-weight-bold" style="white-space: nowrap;">Pension Policy Management</h3>
                <!-- <p class="text-muted mb-0">Configure, audit, and approve dynamic banking pension policies and schemes.</p> -->
            </div>
            <div class="col-sm-8 text-right">
                <a class="btn btn-info mr-2 shadow-sm" href="{{ route('admin.pension.policies.assignments') }}">
                    <i class="fa fa-user-plus mr-1"></i> Policy Assignments
                </a>
                <a class="btn btn-warning mr-2 shadow-sm" href="{{ route('admin.pension.policies.approvals') }}">
                    <i class="fa fa-check-circle mr-1"></i> Approvals Dashboard
                </a>
                <a class="btn btn-secondary mr-2 shadow-sm" href="{{ route('admin.pension.policies.audit-logs') }}">
                    <i class="fa fa-history mr-1"></i> Audit Trail
                </a>
                <a class="btn btn-primary shadow-sm" href="{{ route('admin.pension.policies.create') }}">
                    <i class="fa fa-plus-circle mr-1"></i> Create Policy
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-check mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filtering Bar -->
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pension.policies.index') }}" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label font-weight-bold text-secondary">Policy Code</label>
                    <input type="text" name="code" class="form-control rounded" value="{{ request('code') }}" placeholder="e.g. POL-2026">
                </div>
                <div class="col-md-3">
                    <label class="form-label font-weight-bold text-secondary">Policy Name</label>
                    <input type="text" name="name" class="form-control rounded" value="{{ request('name') }}" placeholder="Search policy name...">
                </div>
                <div class="col-md-2">
                    <label class="form-label font-weight-bold text-secondary">Status</label>
                    <select name="status" class="form-control rounded">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending Approval" {{ request('status') === 'Pending Approval' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label font-weight-bold text-secondary">Retirement Age</label>
                    <input type="number" name="retirement_age" class="form-control rounded" value="{{ request('retirement_age') }}" placeholder="60">
                </div>
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary btn-block rounded shadow-sm">
                        <i class="fa fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light text-secondary font-weight-bold">
                        <tr>
                            <th class="text-left pl-4">Policy Detail</th>
                            <th>Effective Dates</th>
                            <th>Base Formula</th>
                            <th>Retirement Age</th>
                            <th>Schemes Configured</th>
                            <th>Status</th>
                            <th class="pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr>
                            <td class="align-middle text-left pl-4">
                                <span class="badge badge-secondary font-weight-bold py-1 px-2 mb-1">{{ $policy->code }}</span>
                                <h6 class="font-weight-bold mb-0">{{ $policy->name }}</h6>
                                <small class="text-muted d-block">{{ Str::limit($policy->description, 60) }}</small>
                            </td>
                            <td class="align-middle">
                                <strong>From:</strong> {{ \Carbon\Carbon::parse($policy->effective_from)->format('d M Y') }}<br>
                                <small class="text-muted"><strong>To:</strong> {{ $policy->effective_to ? \Carbon\Carbon::parse($policy->effective_to)->format('d M Y') : 'Ongoing' }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border text-secondary px-3 py-1 font-weight-normal">{{ $policy->calculation_base }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="font-weight-bold">{{ $policy->retirement_age }}</span> Years
                            </td>
                            <td class="align-middle">
                                @foreach($policy->schemes as $scheme)
                                    <span class="badge badge-info px-2 py-1 mb-1 font-weight-normal">{{ $scheme->name }} (Emp: {{ $scheme->employee_contribution_percentage }}%, Bank: {{ $scheme->employer_contribution_percentage }}%)</span><br>
                                @endforeach
                            </td>
                            <td class="align-middle">
                                @if($policy->status === 'Active')
                                    <span class="badge badge-success px-3 py-2 rounded-pill font-weight-bold"><i class="fa fa-check-circle mr-1"></i>Active</span>
                                @elseif($policy->status === 'Pending Approval')
                                    <span class="badge badge-warning px-3 py-2 rounded-pill font-weight-bold text-white"><i class="fa fa-clock mr-1"></i>Pending</span>
                                @elseif($policy->status === 'Rejected')
                                    <span class="badge badge-danger px-3 py-2 rounded-pill font-weight-bold"><i class="fa fa-times-circle mr-1"></i>Rejected</span>
                                @else
                                    <span class="badge badge-secondary px-3 py-2 rounded-pill font-weight-bold">Inactive</span>
                                @endif
                            </td>
                            <td class="align-middle pr-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pension.policies.show', $policy->id) }}" class="btn btn-outline-info btn-sm" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pension.policies.edit', $policy->id) }}" class="btn btn-outline-primary btn-sm" title="Edit (Creates New Version)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.pension.policies.clone', $policy->id) }}" class="btn btn-outline-success btn-sm" title="Clone Policy">
                                        <i class="fa fa-clone"></i>
                                    </a>
                                    <a href="{{ route('admin.pension.policies.versions', $policy->id) }}" class="btn btn-outline-secondary btn-sm" title="Version History">
                                        <i class="fa fa-history"></i>
                                    </a>
                                    <form action="{{ route('admin.pension.policies.destroy', $policy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Policy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                                No pension policies configured. Click 'Create Policy' to establish one.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($policies->hasPages())
        <div class="card-footer clearfix bg-white border-0">
            <div class="float-right">
                {{ $policies->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
