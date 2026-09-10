@extends('layouts.default')

{{-- Page title --}}
@section('title')
Loan Repayment Management @parent
@stop

@section('content')
<div class="container-fluid py-4">
    @include('flash::message')

    <!-- Page Header & Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Total Collected</small>
                        <h4 class="mb-0 fw-bold text-success">৳ {{ number_format($metrics['totalCollectedAmount'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="badge p-3 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #d1e7dd; color: #198754; width: 50px; height: 50px;">
                        <i class="im im-icon-Coins fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Active Loans</small>
                        <h4 class="mb-0 fw-bold text-primary">{{ $metrics['activeLoansCount'] ?? 0 }} Loans</h4>
                    </div>
                    <div class="badge p-3 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #cfe2ff; color: #0d6efd; width: 50px; height: 50px;">
                        <i class="im im-icon-Yes fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Total Outstanding</small>
                        <h4 class="mb-0 fw-bold text-danger">৳ {{ number_format($metrics['totalOutstandingAmount'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="badge p-3 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #f8d7da; color: #dc3545; width: 50px; height: 50px;">
                        <i class="im im-icon-Clock fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Total Payments</small>
                        <h4 class="mb-0 fw-bold text-info">{{ $metrics['totalTransactionsCount'] ?? 0 }} Records</h4>
                    </div>
                    <div class="badge p-3 rounded-circle d-flex align-items-center justify-content-center" style="background-color: #cff4fc; color: #0dcaf0; width: 50px; height: 50px;">
                        <i class="im im-icon-Receipt fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title m-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="im im-icon-Receipt text-primary"></i> Loan Repayment Transactions
            </h5>
            <a class="btn btn-primary rounded-pill px-4 shadow-sm" href="{{ route('loanRepayments.create') }}">
                <i class="fa fa-plus me-1"></i> Record New Repayment
            </a>
        </div>
        <div class="card-body p-0">
            @include('loan_repayments.table')
        </div>
        <div class="card-footer bg-white py-3">
            {!! $loanRepayments->links() !!}
        </div>
    </div>
</div>
@endsection
