@extends('layouts.default')

@section('title')
Employee PF Dashboard @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Employee PF Dashboard</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding-left:15px; margin-bottom: 10px;">
                        <h4>My PF Overview</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total PF Balance</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">${{ number_format($totalBalance, 2) }}</h3>
                                    <i class="fa fa-money col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Contributions</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">${{ number_format($employeeSum, 2) }}</h3>
                                    <i class="fa fa-user col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Employer Contributions</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">${{ number_format($employerSum, 2) }}</h3>
                                    <i class="fa fa-building-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Outstanding Loan</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">${{ number_format($outstandingLoan, 2) }}</h3>
                                    <i class="fa fa-exclamation-triangle col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-8">
                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding-left:15px; margin-bottom: 10px;">
                        <h4>Recent Transactions</h4>
                    </div>
                    <div class="card-body p-0 bg-white" style="border-radius: 10px; overflow: hidden; box-shadow: 0px 0px 8px 2px #bdbdbd;">
                        <table class="table table-striped mb-0">
                            <thead style="background: #0177bc; color: white;">
                                <tr>
                                    <th style="padding: 10px; color: #fff;">Date</th>
                                    <th style="padding: 10px; color: #fff;">Description</th>
                                    <th style="padding: 10px; color: #fff;">Credit</th>
                                    <th style="padding: 10px; color: #fff;">Debit</th>
                                    <th style="padding: 10px; color: #fff;">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td class="text-success">{{ number_format($transaction->credit, 2) }}</td>
                                    <td class="text-danger">{{ number_format($transaction->debit, 2) }}</td>
                                    <td>{{ number_format($transaction->balance, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d_card" style="background: aliceblue; height: 100%;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding-left:15px; margin-bottom: 10px;">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body text-center p-0 pt-2 pb-2">
                        <a href="{{ route('pf.loans.index') }}" class="btn btn-primary btn-block mb-3" style="border-radius: 20px; font-weight: bold; font-size: 15px;"><i class="fa fa-plus-circle me-1"></i> Apply for PF Loan</a>
                        <a href="{{ route('pf.loans.index') }}" class="btn btn-info btn-block mb-3 text-white" style="border-radius: 20px; font-weight: bold; font-size: 15px;"><i class="fa fa-list me-1"></i> My Loan Applications</a>
                        <a href="{{ route('pf.reports.statement') }}" class="btn btn-success btn-block" style="border-radius: 20px; font-weight: bold; font-size: 15px; color: #fff;"><i class="fa fa-file-text-o me-1"></i> View PF Statement</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
