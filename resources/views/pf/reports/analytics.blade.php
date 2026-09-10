@extends('layouts.default')

@section('title', 'PF Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">
                {{ ($isEmployee ?? false) ? 'My PF Analytics & Summary' : 'Reports & Analytics' }}
            </h4>
            <div>
                <a href="{{ route('pf.reports.statement') }}" class="btn btn-info">View Individual Statement</a>
            </div>
        </div>
    </div>

    <!-- Metrics row -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-white opacity-75">
                        {{ ($isEmployee ?? false) ? 'My Total PF Balance' : 'Total PF Fund' }}
                    </h6>
                    <h3 class="fw-bold mb-0">৳ {{ number_format($totalFund ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-white opacity-75">
                        {{ ($isEmployee ?? false) ? 'My Last Contribution' : 'Monthly Contributions' }}
                    </h6>
                    <h3 class="fw-bold mb-0">৳ {{ number_format($monthlyContributions ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-dark opacity-75">
                        {{ ($isEmployee ?? false) ? 'My Active Loans' : 'Active Loans' }}
                    </h6>
                    <h3 class="fw-bold mb-0">{{ $activeLoans ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-white opacity-75">
                        {{ ($isEmployee ?? false) ? 'My Pending Withdrawals' : 'Pending Withdrawals' }}
                    </h6>
                    <h3 class="fw-bold mb-0">{{ $pendingWithdrawals ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 fw-bold text-primary">
                    <i class="fa fa-history me-1"></i>
                    {{ ($isEmployee ?? false) ? 'My Recent Transactions' : 'Recent Transactions' }}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-end pe-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions ?? [] as $tx)
                                <tr>
                                    <td>{{ $tx->created_at ? $tx->created_at->format('d M, Y H:i') : 'N/A' }}</td>
                                    <td class="fw-bold">{{ $tx->employee->name ?? 'N/A' }} {{ $tx->employee->last_name ?? '' }}</td>
                                    <td>
                                        <span class="badge {{ strtolower($tx->type ?? '') == 'credit' ? 'bg-success' : 'bg-danger' }}">
                                            {{ strtoupper($tx->type ?? 'LOG') }}
                                        </span>
                                    </td>
                                    <td>{{ $tx->description ?? 'PF Ledger Transaction' }}</td>
                                    <td class="text-end pe-3 fw-bold {{ strtolower($tx->type ?? '') == 'credit' ? 'text-success' : 'text-danger' }}">
                                        {{ strtolower($tx->type ?? '') == 'credit' ? '+' : '-' }}৳ {{ number_format($tx->amount ?? 0, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No transactions recorded.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
