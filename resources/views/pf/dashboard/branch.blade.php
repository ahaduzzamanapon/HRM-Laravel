@extends('layouts.default')

@section('title')
Branch PF Dashboard @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Branch PF Dashboard</h1>
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
                        <h4>Branch PF Overview</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Branch PF Members</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">{{ $totalMembers }}</h3>
                                    <i class="fa fa-users col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="c_card">
                                <div class="col-md-12 p-0">
                                    <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total PF Balance for Branch</h6>
                                </div>
                                <div class="col-md-12 card_flex">
                                    <h3 class="count-all-employees col-md-6">${{ number_format($totalBalance, 2) }}</h3>
                                    <i class="fa fa-money col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
