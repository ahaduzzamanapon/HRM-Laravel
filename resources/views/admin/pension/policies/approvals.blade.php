@extends('layouts.default')

@section('title')
Pension Policy Approvals @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fa fa-check-circle mr-2 text-primary"></i>Approvals Dashboard (Maker-Checker)</h1>
                <p class="text-muted mb-0">Authorize pending policy setups. An approved policy immediately replaces the active system policy.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.pension.policies.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Back to Policies
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

    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-light">
            <h6 class="card-title font-weight-bold text-secondary mb-0"><i class="fa fa-clock mr-1 text-warning"></i>Policies Pending Authorization</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light text-secondary font-weight-bold">
                        <tr>
                            <th class="text-left pl-4">Policy details</th>
                            <th>Calculation Base</th>
                            <th>Effective From</th>
                            <th>Schemes Included</th>
                            <th>Maker (Creator)</th>
                            <th class="pr-4">Authorization Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr>
                            <td class="align-middle text-left pl-4">
                                <span class="badge badge-secondary py-1 px-2 font-weight-bold mb-1">{{ $policy->code }}</span>
                                <h6 class="font-weight-bold mb-0">{{ $policy->name }}</h6>
                                <small class="text-muted d-block">{{ Str::limit($policy->description, 60) }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border text-secondary px-3 py-1">{{ $policy->calculation_base }}</span>
                            </td>
                            <td class="align-middle font-weight-bold">
                                {{ \Carbon\Carbon::parse($policy->effective_from)->format('d M Y') }}
                            </td>
                            <td class="align-middle">
                                @foreach($policy->schemes as $scheme)
                                    <span class="badge badge-info px-2 py-1 mb-1 font-weight-normal">{{ $scheme->name }} ({{ $scheme->employer_contribution_percentage }}%)</span><br>
                                @endforeach
                            </td>
                            <td class="align-middle font-weight-bold text-secondary">
                                <i class="fa fa-user mr-1 text-muted"></i>{{ $policy->creator->name ?? 'System Admin' }}
                            </td>
                            <td class="align-middle pr-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pension.policies.show', $policy->id) }}" class="btn btn-outline-info btn-sm mr-2" title="Review Configuration Details">
                                        <i class="fa fa-search mr-1"></i> Review
                                    </a>
                                    
                                    <form action="{{ route('admin.pension.policies.approve', $policy->id) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('Approving this policy will automatically inactivate the currently active policy. Proceed?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                            <i class="fa fa-check-circle mr-1"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pension.policies.reject', $policy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reject this policy?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                            <i class="fa fa-times-circle mr-1"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa fa-check-double fa-3x mb-3 d-block text-success"></i>
                                All policies are processed. No pending approvals at this time.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
