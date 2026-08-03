@extends('layouts.default')

@section('title')
PF Ledger Report @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>PF Ledger Report</h1>
            </div>
            <div class="col-sm-6" style="text-align: right;">
                <a href="{{ route('pf.dashboard') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 6px 20px;">Back to Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; justify-content: space-between; padding: 0 15px; margin-bottom: 20px;">
                        <h4>Ledger Transactions</h4>
                        
                        <!-- Simple Filter Form -->
                        <form action="{{ route('pf.reports.ledger') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2 mb-2">
                                <input type="text" name="employee_id" class="form-control" placeholder="Employee ID" value="{{ request('employee_id') }}" style="border-radius: 20px;">
                            </div>
                            <div class="form-group mr-2 mb-2">
                                <input type="text" name="branch_id" class="form-control" placeholder="Branch ID" value="{{ request('branch_id') }}" style="border-radius: 20px;">
                            </div>
                            <button type="submit" class="btn btn-primary mb-2" style="border-radius: 20px;"><i class="fa fa-filter"></i> Filter</button>
                            <a href="{{ route('pf.reports.ledger') }}" class="btn btn-light mb-2 ml-2" style="border-radius: 20px;">Clear</a>
                        </form>
                    </div>

                    <div class="card-body p-0 bg-white" style="border-radius: 10px; overflow: hidden; box-shadow: 0px 0px 8px 2px #bdbdbd;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead style="background: #0177bc; color: white;">
                                    <tr>
                                        <th style="padding: 10px; color: #fff;">Date</th>
                                        <th style="padding: 10px; color: #fff;">Employee</th>
                                        <th style="padding: 10px; color: #fff;">Branch</th>
                                        <th style="padding: 10px; color: #fff;">Type</th>
                                        <th style="padding: 10px; color: #fff;">Description</th>
                                        <th style="padding: 10px; color: #fff;">Credit</th>
                                        <th style="padding: 10px; color: #fff;">Debit</th>
                                        <th style="padding: 10px; color: #fff;">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ledgers as $ledger)
                                    <tr>
                                        <td>{{ $ledger->created_at->format('Y-m-d') }}</td>
                                        <td>{{ optional($ledger->employee)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($ledger->branch)->branch_name ?? optional($ledger->branch)->name ?? 'N/A' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $ledger->transaction_type)) }}</td>
                                        <td>{{ $ledger->description }}</td>
                                        <td class="text-success">{{ number_format($ledger->credit, 2) }}</td>
                                        <td class="text-danger">{{ number_format($ledger->debit, 2) }}</td>
                                        <td style="font-weight: bold;">{{ number_format($ledger->balance, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No ledger transactions found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $ledgers->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
