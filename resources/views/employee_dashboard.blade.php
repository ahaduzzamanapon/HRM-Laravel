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
@php
    $profileUrl = route('profile');
    $leaveUrl = Route::has('leaveApplications.index') ? route('leaveApplications.index') : $profileUrl;
    $loanUrl = Route::has('loans.index') ? route('loans.index') : $profileUrl;
    $providentFundUrl = (can('view_provident_fund_statements') && Route::has('providentFunds.index')) ? route('providentFunds.index') : $profileUrl;
    $childAllowanceUrl = (can('manage_employee_children_education_supports') && Route::has('employeeChildrenEducationSupports.index')) ? route('employeeChildrenEducationSupports.index') : (Route::has('childAllowances.index') ? route('childAllowances.index') : $profileUrl);
@endphp
<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua" style="cursor: pointer;" onclick="window.location.href='{{ $leaveUrl }}'">
                <div class="inner">
                    <h3>{{ $totalLeaveApplications }}</h3>
                    <p>My Leave Applications</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar-alt"></i>
                </div>
                <a href="{{ $leaveUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow" style="cursor: pointer;" onclick="window.location.href='{{ $leaveUrl }}'">
                <div class="inner">
                    <h3>{{ $pendingLeaveApplications }}</h3>
                    <p>Pending Leave</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar-day"></i>
                </div>
                <a href="{{ $leaveUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green" style="cursor: pointer;" onclick="window.location.href='{{ $leaveUrl }}'">
                <div class="inner">
                    <h3>{{ $approvedLeaveApplications }}</h3>
                    <p>Approved Leave</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check-circle"></i>
                </div>
                <a href="{{ $leaveUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red" style="cursor: pointer;" onclick="window.location.href='{{ $leaveUrl }}'">
                <div class="inner">
                    <h3>{{ $rejectedLeaveApplications }}</h3>
                    <p>Rejected Leave</p>
                </div>
                <div class="icon">
                    <i class="fa fa-times-circle"></i>
                </div>
                <a href="{{ $leaveUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua" style="cursor: pointer;" onclick="window.location.href='{{ $loanUrl }}'">
                <div class="inner">
                    <h3>{{ $totalLoans }}</h3>
                    <p>My Loans</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-alt"></i>
                </div>
                <a href="{{ $loanUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow" style="cursor: pointer;" onclick="window.location.href='{{ $loanUrl }}'">
                <div class="inner">
                    <h3>{{ $pendingLoans }}</h3>
                    <p>My Pending Loans</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-wave"></i>
                </div>
                <a href="{{ $loanUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #605ca8; color: white; cursor: pointer;" onclick="window.location.href='{{ $profileUrl }}'">
                <div class="inner">
                    <h3>{{ $mySalaryGrade }}</h3>
                    <p>My Salary Grade</p>
                </div>
                <div class="icon">
                    <i class="fa fa-stairs"></i>
                </div>
                <a href="{{ $profileUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #0073b7; color: white; cursor: pointer;" onclick="window.location.href='{{ $providentFundUrl }}'">
                <div class="inner">
                    <h3>{{ $myProvidentFund }}</h3>
                    <p>My Provident Fund</p>
                </div>
                <div class="icon">
                    <i class="fa fa-piggy-bank"></i>
                </div>
                <a href="{{ $providentFundUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #ff851b; color: white; cursor: pointer;" onclick="window.location.href='{{ $childAllowanceUrl }}'">
                <div class="inner">
                    <h3>{{ $myChildren }}</h3>
                    <p>My Children for Allowance</p>
                </div>
                <div class="icon">
                    <i class="fa fa-child"></i>
                </div>
                <a href="{{ $childAllowanceUrl }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
</section>
@stop
