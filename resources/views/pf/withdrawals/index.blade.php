@extends('layouts.default')

@section('title', 'PF Withdrawals')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">PF Withdrawal Requests</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWithdrawalModal">Add New Request</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                                    <th>PF Account No</th>
                                    <th>Type</th>
                                    <th>Requested Amount</th>
                                    <th>Approved Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $index => $withdrawal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $withdrawal->employee->name ?? 'N/A' }} {{ $withdrawal->employee->last_name ?? '' }}</td>
                                    <td>{{ $withdrawal->employee->pf_account_number ?? 'N/A' }}</td>
                                    <td>{{ $withdrawal->type }}</td>
                                    <td>{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>{{ $withdrawal->approved_amount ? number_format($withdrawal->approved_amount, 2) : 'N/A' }}</td>
                                    <td>
                                        @if($withdrawal->status == 'Pending')
                                            <span class="badge bg-warning text-dark">{{ $withdrawal->status }}</span>
                                        @elseif($withdrawal->status == 'HR Approved')
                                            <span class="badge bg-info">{{ $withdrawal->status }}</span>
                                        @elseif($withdrawal->status == 'Disbursed')
                                            <span class="badge bg-success">{{ $withdrawal->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $withdrawal->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal->status == 'Pending')
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $withdrawal->id }}">Approve</button>
                                        @elseif($withdrawal->status == 'HR Approved')
                                            <form action="{{ route('pf.withdrawals.disburse', $withdrawal->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Disburse</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $withdrawal->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('pf.withdrawals.approve', $withdrawal->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Withdrawal Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Requested Amount</label>
                                                        <input type="number" class="form-control" value="{{ $withdrawal->amount }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Approved Amount <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control" name="approved_amount" value="{{ $withdrawal->amount }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Withdrawal Modal -->
<div class="modal fade" id="addWithdrawalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pf.withdrawals.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Withdrawal Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->emp_id ? $employee->emp_id . ' - ' : '' }}{{ $employee->name }} {{ $employee->last_name }} ({{ $employee->pf_account_number ?? 'No PF Acc' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Withdrawal Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="Partial">Partial</option>
                            <option value="Full">Full</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason / Remarks <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Request</button>
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
