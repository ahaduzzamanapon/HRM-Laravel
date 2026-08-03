@extends('layouts.default')

@section('title')
Admin PF Dashboard @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Admin PF Dashboard</h1>
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
                        <h4>PF Overview</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total PF Members</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">{{ $totalMembers }}</h3>
                                    <i class="fa fa-users col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
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
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Pending Loans</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">{{ $pendingLoans }}</h3>
                                    <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Pending Withdrawals</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">{{ $pendingWithdrawals }}</h3>
                                    <i class="fa fa-file-text-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding-left:15px; margin-bottom: 10px;">
                        <h4>Quick Links</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pb-3">
                            <a href="{{ route('pf.schemes.index') }}" class="btn btn-primary m-1" style="border-radius: 20px; padding: 10px 20px;">Manage Schemes</a>
                            <a href="{{ route('pf.contributions.index') }}" class="btn btn-success m-1" style="border-radius: 20px; padding: 10px 20px;">Process Contributions</a>
                            <a href="{{ route('pf.loans.index') }}" class="btn btn-warning m-1" style="border-radius: 20px; padding: 10px 20px;">Manage Loans</a>
                            <a href="{{ route('pf.withdrawals.index') }}" class="btn btn-info m-1" style="border-radius: 20px; padding: 10px 20px;">Manage Withdrawals</a>
                            <a href="{{ route('pf.settlements.index') }}" class="btn btn-danger m-1" style="border-radius: 20px; padding: 10px 20px;">Manage Settlements</a>
                            <a href="{{ route('pf.reports.ledger') }}" class="btn btn-secondary m-1" style="border-radius: 20px; padding: 10px 20px;">Ledger Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
