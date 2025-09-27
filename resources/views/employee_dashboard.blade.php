@extends('layouts.default')
{{-- Page title --}}
@section('title')
Dashboard @parent
@stop
@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<section class="content-header">
    <h1>
        Dashboard
        <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">
            <a href="{{ url('/') }}">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $totalLeaveApplications }}</h3>
                    <p>My Leave Applications</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar-alt"></i>
                </div>
                <a href="{{ route('leaveApplications.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $pendingLeaveApplications }}</h3>
                    <p>My Pending Leave Applications</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar-day"></i>
                </div>
                <a href="{{ route('leaveApplications.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $totalLoans }}</h3>
                    <p>My Loans</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-alt"></i>
                </div>
                <a href="{{ route('loans.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $pendingLoans }}</h3>
                    <p>My Pending Loans</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('loans.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #605ca8; color: white;">
                <div class="inner">
                    <h3>{{ $mySalaryGrade }}</h3>
                    <p>My Salary Grade</p>
                </div>
                <div class="icon">
                    <i class="fa fa-stairs"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #0073b7; color: white;">
                <div class="inner">
                    <h3>{{ $myProvidentFund }}</h3>
                    <p>My Provident Fund</p>
                </div>
                <div class="icon">
                    <i class="fa fa-piggy-bank"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #ff851b; color: white;">
                <div class="inner">
                    <h3>{{ $myChildren }}</h3>
                    <p>My Children for Allowance</p>
                </div>
                <div class="icon">
                    <i class="fa fa-child"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
</section>
@stop