@extends('layouts.default')

@section('title')
Pension Policy Assignments @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h3 class="font-weight-bold">Pension Policy Assignments</h3>
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

    <div class="row">
        <!-- Assign form column -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header bg-light">
                    <h6 class="card-title font-weight-bold text-secondary">New Scheme Assignment</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pension.policies.assignments.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Select Employee <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control select2 rounded" required>
                                <option value="">-- Choose Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" data-salary="{{ $emp->basic_salary ?: $emp->gross_salary ?: 0 }}">{{ trim($emp->name . ' ' . $emp->last_name) }} ({{ $emp->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Select Pension Scheme <span class="text-danger">*</span></label>
                            <select name="scheme_id" class="form-control rounded" required>
                                <option value="">-- Choose Scheme --</option>
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}">{{ $scheme->name }} (Policy: {{ $scheme->policy->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Last Basic Pay (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="last_basic_pay" id="last_basic_pay" class="form-control rounded" required placeholder="0.00">
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Service Years <span class="text-danger">*</span></label>
                                <input type="number" name="qualifying_service_years" class="form-control rounded" required min="0" max="50" placeholder="e.g. 25">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Service Months <span class="text-danger">*</span></label>
                                <input type="number" name="qualifying_service_months" class="form-control rounded" required min="0" max="11" placeholder="e.g. 6">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Retirement Date</label>
                            <input type="date" name="retirement_date" class="form-control rounded" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Retirement Type</label>
                            <select name="retirement_type" class="form-control rounded">
                                <option value="Superannuation">Superannuation</option>
                                <option value="Voluntary Retirement">Voluntary Retirement</option>
                                <option value="Compulsory Retirement">Compulsory Retirement</option>
                                <option value="Resignation">Resignation</option>
                                <option value="Medical Ground">Medical Ground</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4 shadow-sm font-weight-bold">
                            <i class="fa fa-save mr-1"></i> Confirm & Assign
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assignments list column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header bg-light">
                    <h6 class="card-title font-weight-bold text-secondary mb-0"><i class="fa fa-users mr-1 text-info"></i>Active Assignments</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-center">
                            <thead class="bg-light text-secondary font-weight-bold">
                                <tr>
                                    <th class="text-left pl-3">Employee Name</th>
                                    <th>Assigned Scheme</th>
                                    <th>Last Basic Pay</th>
                                    <th>Service Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assign)
                                <tr>
                                    <td class="align-middle text-left pl-3 font-weight-bold">
                                        {{ isset($assign->user) ? trim($assign->user->name . ' ' . $assign->user->last_name) : 'User Not Found' }}<br>
                                        <small class="text-muted">{{ $assign->user->email ?? '' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-info px-2 py-1">{{ $assign->scheme->name ?? 'Scheme Not Found' }}</span><br>
                                        <small class="text-muted">Policy: {{ $assign->scheme->policy->code ?? '' }}</small>
                                    </td>
                                    <td class="align-middle font-weight-bold text-secondary">
                                        ৳{{ number_format($assign->last_basic_pay, 2) }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $assign->qualifying_service_years }} Y, {{ $assign->qualifying_service_months }} M
                                    </td>
                                    <td class="align-middle">
                                        @if($assign->eligibility_status === 'Eligible')
                                            <span class="badge badge-success px-2 py-1 rounded font-weight-bold">Eligible</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1 rounded font-weight-bold">Not Eligible</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No employee scheme assignments exist.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($assignments->hasPages())
                <div class="card-footer bg-white border-0 clearfix">
                    <div class="float-right">
                        {{ $assignments->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto pull basic salary of selected user
        $('select[name="user_id"]').change(function() {
            var salary = $(this).find('option:selected').data('salary');
            if (salary) {
                $('#last_basic_pay').val(salary);
            } else {
                $('#last_basic_pay').val('');
            }
        });
    });
</script>
@endpush
@endsection
