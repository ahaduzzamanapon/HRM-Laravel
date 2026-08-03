@extends('layouts.default')

{{-- Page title --}}
@section('title')
Employee Transfers @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
</section>

<!-- Main content -->
<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card" width="88vw;">
        <section class="card-header">
            <h5 class="card-title d-inline">Employee Transfer Records</h5>
            <span class="float-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}">
                    <i class="fa fa-users"></i> Users List
                </a>
            </span>
        </section>
        <div class="card-body table-responsive">
            <table class="table table-hover table-striped table_data" id="transfer-details-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Emp Id</th>
                        <th>Name</th>
                        <th>Transfer Date</th>
                        <th>From Branch</th>
                        <th>To Branch</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Document</th>
                        <th data-orderable="false">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transferDetails as $key => $transfer)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $transfer->user->emp_id ?? 'N/A' }}</td>
                        <td>{{ $transfer->user->name ?? 'N/A' }} {{ $transfer->user->last_name ?? '' }}</td>
                        <td>{{ $transfer->transfer_date ? \Carbon\Carbon::parse($transfer->transfer_date)->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $transfer->oldBranchName->branch_name ?? ($transfer->old_branch ?? 'N/A') }}</td>
                        <td>{{ $transfer->newBranchName->branch_name ?? ($transfer->new_branch ?? 'N/A') }}</td>
                        <td>{{ $transfer->reason ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $transfer->status == 'Approved' ? 'success' : ($transfer->status == 'Rejected' ? 'danger' : 'warning') }}">
                                {{ $transfer->status ?? 'Pending' }}
                            </span>
                        </td>
                        <td>
                            @if($transfer->document)
                                <a href="{{ asset($transfer->document) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                    <i class="fa fa-file"></i> View
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <div class='btn-group'>
                                @if($transfer->user_id)
                                    <a href="{{ route('users.edit', [$transfer->user_id]) }}" class='btn btn-info btn-xs' title="Edit User">
                                        <i class="fa fa-edit"></i> Profile
                                    </a>
                                @endif
                                {!! Form::open(['route' => ['transferDetails.destroy', $transfer->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this transfer record?')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $transferDetails->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
