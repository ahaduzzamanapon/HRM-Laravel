@extends('layouts.default')

@section('title')
Pension Calculations @parent
@stop

@section('content')
<section class="content-header py-2 mb-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0 font-weight-bold" style="color: #2c3e50;">Pension Calculations</h3>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size: 0.9rem; border-left: 4px solid #28a745;">
            <i class="fa fa-check mr-2"></i> {{ session('success') }}
            <button type="button" class="close py-2" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="clearfix"></div>

    <div class="card shadow-sm border-0 mb-3 bg-white">
        <div class="card-body py-2 px-3">
            <div class="row align-items-center">
                <!-- Left: Filter Form -->
                <div class="col-md-7">
                    <form action="{{ route('admin.pension.calculations.index') }}" method="POST" class="form-inline d-flex flex-wrap align-items-center">
                        @csrf
                        <span class="mr-2 text-secondary font-weight-bold" style="font-size: 0.85rem;"><i class="fa fa-filter"></i> Filter List:</span>
                        <select name="filter_year" id="filter_year" class="form-control form-control-sm mr-2" style="width: 110px;">
                            <option value="">All Years</option>
                            @for($y = date('Y') - 10; $y <= date('Y') + 20; $y++)
                                <option value="{{ $y }}" {{ request('filter_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <select name="filter_month" id="filter_month" class="form-control form-control-sm mr-2" style="width: 120px;">
                            <option value="">All Months</option>
                            @foreach(['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'] as $m => $name)
                                <option value="{{ $m }}" {{ request('filter_month') == $m ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm mr-1 py-1" style="font-size: 0.85rem;">
                            Filter
                        </button>
                        @if(request('filter_year') || request('filter_month'))
                            <a href="{{ route('admin.pension.calculations.index') }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.85rem;">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
                <!-- Right: Process Calculations Form -->
                <div class="col-md-5 text-right border-left">
                    <form action="{{ route('admin.pension.calculations.process') }}" method="POST" class="form-inline justify-content-end d-flex flex-wrap align-items-center">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm py-1" onclick="return confirm('Calculate pension for all eligible uncalculated profiles?')" style="font-size: 0.85rem;">
                            <i class="fa fa-calculator"></i> Process Calculations
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Employee</th>
                            <th>Gross Pension</th>
                            <th>Commuted Amt</th>
                            <th>Medical Allow.</th>
                            <th>Monthly Pension</th>
                            <th>Total Pension</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calculations as $calc)
                        <tr>
                            <td class="align-middle">{{ ($calculations->currentPage() - 1) * $calculations->perPage() + $loop->iteration }}</td>
                            <td class="align-middle text-left pl-3">
                                @if($calc->profile && $calc->profile->user)
                                    <strong>{{ trim($calc->profile->user->name . ' ' . $calc->profile->user->last_name) }}</strong><br>
                                    @if($calc->profile->departure && $calc->profile->departure->effective_date)
                                        <small class="text-danger font-weight-bold">Retires: {{ \Carbon\Carbon::parse($calc->profile->departure->effective_date)->format('M Y') }}</small>
                                    @elseif($calc->profile->retirement_date)
                                        <small class="text-muted">Retires: {{ \Carbon\Carbon::parse($calc->profile->retirement_date)->format('M Y') }}</small>
                                    @else
                                        <small class="text-muted">Retires: N/A</small>
                                    @endif
                                @else
                                    <strong>Profile Deleted</strong><br>
                                    <small class="text-muted">Retires: N/A</small>
                                @endif
                            </td>
                            <td class="align-middle">৳{{ number_format($calc->gross_pension, 2) }}</td>
                            <td class="align-middle">৳{{ number_format($calc->commuted_amount, 2) }}</td>
                            <td class="align-middle">৳{{ number_format($calc->medical_allowance, 2) }}</td>
                            <td class="align-middle text-success fw-bold">৳{{ number_format($calc->net_pension, 2) }}</td>
                            <td class="align-middle fw-bold">৳{{ ($calc->commuted_amount + $calc->net_pension) }}</td>
                            <td class="align-middle">
                                @php
                                    $badgeClass = 'badge-secondary';
                                    if($calc->status == 'Finalized') $badgeClass = 'badge-success';
                                    if($calc->status == 'Approved') $badgeClass = 'badge-primary';
                                    if($calc->status == 'Draft') $badgeClass = 'badge-warning';
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $calc->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No calculations found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $calculations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
