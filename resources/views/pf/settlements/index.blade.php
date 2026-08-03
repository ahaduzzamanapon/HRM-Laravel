@extends('layouts.default')

@section('title')
PF Settlements @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>PF Settlements</h1>
            </div>
            <div class="col-sm-6" style="text-align: right;">
                <a href="{{ route('pf.settlements.create') }}" class="btn btn-primary" style="border-radius: 20px; padding: 6px 20px;"><i class="fa fa-plus"></i> Initiate Settlement</a>
                <a href="{{ route('pf.dashboard') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 6px 20px;">Back to Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding: 0 15px; margin-bottom: 20px;">
                        <h4>Manage Settlements</h4>
                    </div>

                    <div class="card-body p-0 bg-white" style="border-radius: 10px; overflow: hidden; box-shadow: 0px 0px 8px 2px #bdbdbd;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead style="background: #0177bc; color: white;">
                                    <tr>
                                        <th style="padding: 10px; color: #fff;">Date</th>
                                        <th style="padding: 10px; color: #fff;">Employee</th>
                                        <th style="padding: 10px; color: #fff;">Reason</th>
                                        <th style="padding: 10px; color: #fff;">Total Balance</th>
                                        <th style="padding: 10px; color: #fff;">Settlement Amt</th>
                                        <th style="padding: 10px; color: #fff;">Status</th>
                                        <th style="padding: 10px; color: #fff; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($settlements as $settlement)
                                    <tr>
                                        <td>{{ $settlement->created_at->format('Y-m-d') }}</td>
                                        <td>{{ optional($settlement->employee)->name ?? 'N/A' }}</td>
                                        <td>{{ $settlement->reason }}</td>
                                        <td>${{ number_format($settlement->total_balance, 2) }}</td>
                                        <td>${{ number_format($settlement->settlement_amount, 2) }}</td>
                                        <td>
                                            @if($settlement->status === 'Pending')
                                                <span class="badge badge-warning" style="background: #ffc107; padding: 5px 10px; color: #212529;">Pending</span>
                                            @elseif($settlement->status === 'Settled')
                                                <span class="badge badge-success" style="background: #28a745; padding: 5px 10px; color: #fff;">Settled</span>
                                            @else
                                                <span class="badge badge-secondary" style="background: #6c757d; padding: 5px 10px; color: #fff;">{{ $settlement->status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if($settlement->status === 'Pending')
                                            <form action="{{ route('pf.settlements.process', $settlement->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" style="border-radius: 15px;" onclick="return confirm('Are you sure you want to process this settlement? This will bring the balance to 0.')">Process Settlement</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No settlement requests found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $settlements->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
