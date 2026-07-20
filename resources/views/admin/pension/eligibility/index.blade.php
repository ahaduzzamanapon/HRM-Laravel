@extends('layouts.default')

@section('title')
Eligibility Checks @parent
@stop

@section('content')
<section class="content-header py-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="mb-0">Pension Eligibility Checks</h3>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert" style="padding: 0.5rem 1rem;">
            <i class="fa fa-check mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 0.5rem 1rem;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert" style="padding: 0.5rem 1rem;">
            <i class="fa fa-times mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 0.5rem 1rem;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-3 bg-white">
        <div class="card-body py-2 px-3">
            <div class="row align-items-center">
                <!-- Left: Filter Form -->
                <div class="col-md-7">
                    <form action="{{ route('admin.pension.eligibility.index') }}" method="POST" class="form-inline d-flex flex-wrap align-items-center">
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
                            <a href="{{ route('admin.pension.eligibility.index') }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.85rem;">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
                <!-- Right: Run Check Form -->
                <div class="col-md-5 text-right border-left">
                    <form action="{{ route('admin.pension.eligibility.run-check') }}" method="POST" class="form-inline justify-content-end d-flex flex-wrap align-items-center">
                        @csrf
                        <span class="mr-2 text-primary font-weight-bold" style="font-size: 0.85rem;"><i class="fa fa-cogs"></i> Run Check:</span>
                        <select name="year" class="form-control form-control-sm mr-2" style="width: 100px;">
                            <option value="">All Years</option>
                            @for($y = date('Y') - 5; $y <= date('Y') + 10; $y++)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <select name="month" class="form-control form-control-sm mr-2" style="width: 110px;">
                            <option value="">All Months</option>
                            @foreach(['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'] as $m => $name)
                                <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm py-1" onclick="return confirm('Run automated check for the selected year and month?')" style="font-size: 0.85rem;">
                            Run
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
                            <th>Sl.</th>
                            <th>Employee</th>
                            <th>Scheme</th>
                            <th>Service Yrs</th>
                            <th>Basic Pay</th>
                            <th>Age Valid</th>
                            <th>Srv Valid</th>
                            <th>Overall Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profiles as $profile)
                        <tr>
                            <td>{{@$i = @$i+1}}</td>
                             <td class="align-middle text-left pl-3">
                                <strong>{{ $profile->user ? trim($profile->user->name . ' ' . $profile->user->last_name) : 'N/A' }}</strong><br>
                                @if($profile->departure && $profile->departure->effective_date)
                                    <small class="text-danger font-weight-bold">Retires: {{ \Carbon\Carbon::parse($profile->departure->effective_date)->format('M Y') }}</small>
                                @elseif($profile->retirement_date)
                                    <small class="text-muted">Retires: {{ \Carbon\Carbon::parse($profile->retirement_date)->format('M Y') }}</small>
                                @else
                                    <small class="text-muted">Retires: N/A</small>
                                @endif
                            </td>
                            <td class="align-middle">{{ $profile->scheme->name ?? 'N/A' }}</td>
                            <td class="align-middle">{{ $profile->qualifying_service_years }} yrs {{ $profile->qualifying_service_months }} mos</td>
                            <td class="align-middle text-success fw-bold">৳{{ number_format($profile->last_basic_pay, 2) }}</td>
                            <td class="align-middle">
                                @if(optional($profile->eligibilityCheck)->age_validated)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(optional($profile->eligibilityCheck)->service_years_validated)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td class="align-middle">
                                @php
                                    $status = optional($profile->eligibilityCheck)->overall_status ?? 'Pending';
                                    $badgeClass = 'badge-secondary';
                                    if($status == 'Pass') $badgeClass = 'badge-success';
                                    if($status == 'Fail') $badgeClass = 'badge-danger';
                                    if($status == 'Pending') $badgeClass = 'badge-warning';
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-3 d-block"></i>
                                No eligibility checks found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $profiles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
