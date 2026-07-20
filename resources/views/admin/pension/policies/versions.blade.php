@extends('layouts.default')

@section('title')
Pension Policy Version History @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fa fa-history mr-2 text-primary"></i>Version History: {{ $policy->code }}</h1>
                <p class="text-muted mb-0">Trace previous revisions, change logs, and configurations of this policy.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.pension.policies.show', $policy->id) }}" class="btn btn-info mr-2 shadow-sm">
                    <i class="fa fa-eye mr-1"></i> View Current Details
                </a>
                <a href="{{ route('admin.pension.policies.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 text-center">
                    <thead class="bg-light text-secondary font-weight-bold">
                        <tr>
                            <th>Version Number</th>
                            <th>Effective Date</th>
                            <th class="text-left pl-4">Change Summary / Revision Notes</th>
                            <th>Modified By</th>
                            <th>Logged Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($versions as $version)
                        <tr>
                            <td class="align-middle">
                                <span class="badge badge-info px-3 py-2 font-weight-bold">V{{ $version->version_number }}</span>
                            </td>
                            <td class="align-middle font-weight-bold">
                                {{ \Carbon\Carbon::parse($version->effective_date)->format('d M Y') }}
                            </td>
                            <td class="align-middle text-left pl-4">
                                <div class="font-weight-bold">{{ $version->change_summary }}</div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border px-2 py-1"><i class="fa fa-user mr-1 text-secondary"></i>{{ $version->creator->name ?? 'System Admin' }}</span>
                            </td>
                            <td class="align-middle text-muted">
                                {{ \Carbon\Carbon::parse($version->created_at)->format('d M Y h:i A') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa fa-history fa-3x mb-3 d-block text-secondary"></i>
                                No previous version history logged for this policy.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($versions->hasPages())
        <div class="card-footer bg-white border-0 clearfix">
            <div class="float-right">
                {{ $versions->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
