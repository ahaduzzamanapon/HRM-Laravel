@extends('layouts.default')

@section('title')
Pension Policy Audit Trail @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fa fa-history mr-2 text-primary"></i>Pension Policy Audit Trail</h1>
                <p class="text-muted mb-0">Trace history logs, actions, and previous JSON attribute state values for policy compliance audits.</p>
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
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light text-secondary font-weight-bold">
                        <tr>
                            <th>Action Type</th>
                            <th>Target Entity</th>
                            <th>User Agent</th>
                            <th>IP Address</th>
                            <th>Logged At</th>
                            <th>State details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="align-middle">
                                @if($log->action === 'Create')
                                    <span class="badge badge-success px-2 py-1"><i class="fa fa-plus-circle mr-1"></i>Create</span>
                                @elseif($log->action === 'Update')
                                    <span class="badge badge-primary px-2 py-1"><i class="fa fa-edit mr-1"></i>Update</span>
                                @elseif($log->action === 'Approve')
                                    <span class="badge badge-info px-2 py-1 text-white"><i class="fa fa-check mr-1"></i>Approve</span>
                                @elseif($log->action === 'Reject')
                                    <span class="badge badge-danger px-2 py-1"><i class="fa fa-times mr-1"></i>Reject</span>
                                @elseif($log->action === 'Delete')
                                    <span class="badge badge-danger px-2 py-1"><i class="fa fa-trash mr-1"></i>Delete</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">{{ $log->action }}</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="font-weight-bold">{{ class_basename($log->model_type) }}</span> (ID: {{ $log->model_id }})
                            </td>
                            <td class="align-middle font-weight-bold text-secondary">
                                <i class="fa fa-user mr-1 text-muted"></i>{{ $log->user->name ?? 'System Admin' }}
                            </td>
                            <td class="align-middle text-secondary font-weight-bold">
                                {{ $log->ip_address }}
                            </td>
                            <td class="align-middle text-muted">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}
                            </td>
                            <td class="align-middle">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#logModal{{ $log->id }}">
                                    <i class="fa fa-search-plus mr-1"></i> JSON Diff
                                </button>

                                <!-- State values Modal -->
                                <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title font-weight-bold"><i class="fa fa-code mr-2 text-primary"></i>Audit State: {{ $log->action }} (Log ID: {{ $log->id }})</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <div class="row">
                                                    @if($log->old_values)
                                                    <div class="col-md-6 mb-3">
                                                        <label class="font-weight-bold text-danger">Previous Configuration State</label>
                                                        <pre class="bg-dark text-white p-3 rounded" style="max-height: 400px; overflow-y: auto; font-size: 0.85rem;"><code>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</code></pre>
                                                    </div>
                                                    @endif
                                                    
                                                    <div class="col-md-{{ $log->old_values ? '6' : '12' }} mb-3">
                                                        <label class="font-weight-bold text-success">New/Updated Configuration State</label>
                                                        <pre class="bg-dark text-white p-3 rounded" style="max-height: 400px; overflow-y: auto; font-size: 0.85rem;"><code>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa fa-history fa-3x mb-3 d-block text-secondary"></i>
                                No audit logs recorded.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
        <div class="card-footer bg-white border-0 clearfix">
            <div class="float-right">
                {{ $logs->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
