@extends('layouts.default')

@section('title', 'PF Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">Reports & Analytics</h4>
            <div>
                <a href="{{ route('pf.reports.statement') }}" class="btn btn-info">View Individual Statement</a>
            </div>
        </div>
    </div>

    <!-- Metrics row -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Total PF Fund</h5>
                    <h3>$ 1,250,000.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Monthly Contributions</h5>
                    <h3>$ 45,000.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title text-dark">Active Loans</h5>
                    <h3>12</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Pending Withdrawals</h5>
                    <h3>5</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Fund Growth (Last 6 Months)</div>
                <div class="card-body">
                    <!-- Placeholder for Chart -->
                    <div style="height: 300px; background: #f8f9fa; display:flex; align-items:center; justify-content:center; border:1px dashed #ccc;">
                        <span class="text-muted">Chart Placeholder: Use Chart.js or ApexCharts here</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Recent Transactions</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Contribution - John Doe
                            <span class="text-success">+$ 1,500</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Withdrawal - Jane Smith
                            <span class="text-danger">-$ 2,000</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Loan Disbursed - Mike Ross
                            <span class="text-danger">-$ 5,000</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
