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
        {{-- Daily Attendance --}}
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-6"> Daily Attendance</h4>
                    <input class="col-md-3" type="date" onchange="get_data_count()" value="2025-10-06" name="date" id="date_first_card" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;">
                    <div class="col-md-3">
                        <a onclick="daily_report('all')" class="btn btn-primary btn-sm text-white" style="text-align: -webkit-center; cursor: pointer;">Get Report <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" id="all-employees">
                            <h6 style="font-size:15px !important;">All Employees</h6>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count-all-employees">{{ $totalEmployees }}</h3>
                                <i class="fa fa-user col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('users.index') }}"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card" id="present">
                            <h6 style="font-size:15px !important;">Present</h6>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-present col-md-6" id="count-present">0</h3>
                                <i class="fa fa-laptop col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" id="absent">
                            <h6 style="font-size:15px !important;">Absent</h6>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-absent col-md-6" id="count-absent">0</h3>
                                <i class="fa fa-home col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card" id="late">
                            <h6 style="font-size:15px !important;">Late</h6>
                            <div class="col-md-12 card_flex" >
                                <h3 class="count-late col-md-6" id="count-late">0</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Attendance --}}
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-6">Monthly</h4>
                    <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 card_flex">
                                <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Leave</h6>
                                <a href="javascript:void(0)" onclick="get_leave_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_leave_monthly">0</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 card_flex">
                                <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Extra Present</h6>
                                <a href="javascript:void(0)" onclick="get_extra_present_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">0</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 card_flex">
                                <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Late</h6>
                                <a href="javascript:void(0)" onclick="get_late_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_late_monthly">0</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 card_flex">
                                <h6 class="col-md-6 p-0" style="font-size: 15px !important;">Meeting</h6>
                                <a href="javascript:void(0)" onclick="get_meeting_monthly(event)" class="col-md-6 p-0" style="text-align: -webkit-center;cursor: pointer;;">Get Report
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_meeting_monthly">0</h3>
                                <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    
    <div class="row">
        {{-- office info  --}}
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-6">Organization Info</h4>
                    {{-- <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;"> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Employees</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_leave_monthly">{{ $totalEmployees }}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="c_card">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Branch</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">{{ $totalBranches }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Department</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_late_monthly">{{ $totalDepartments }}</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">New Emps.(Last 30 Days)</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_meeting_monthly">{{ $newEmployees }}</h3>
                                <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Leave info  --}}
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-6">Leave Info</h4>
                    {{-- <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;"> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Leave Apply</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_leave_monthly">{{ $totalLeaveApplications }}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('leaveApplications.index') }}"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="c_card">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Pending</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">{{ $totalLeaveApplications }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('leaveApplications.index') }}"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Approved</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_late_monthly">{{ $totalLeaveApplications }}</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('leaveApplications.index') }}"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Rejected</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_meeting_monthly">{{ $totalLeaveApplications }}</h3>
                                <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('leaveApplications.index') }}"style="cursor: pointer;text-decoration: none!important;text-align: -webkit-center;" >More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-6">Payroll Info</h4>
                    {{-- <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;"> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Salary Grade</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_leave_monthly">{{ $totalSalaryGrades }}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('salaryGrades.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Tax Setups</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">{{ $totalTaxSetups }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('taxSetups.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Total Loans</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_late_monthly">{{ $totalLoans }}</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                                <a href="{{ route('loans.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Pending Loans</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_meeting_monthly">{{ $pendingLoans }}</h3>
                                <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="{{ route('loans.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-12">Allowance & Provident Fund</h4>
                    {{-- <input class="col-md-4" type="month" onchange="get_monthly_data()" value="2025-10" id="date_monthly" style="border: 1px solid #009cf5;background: transparent;padding: 3px;border-radius: 7px;"> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0 ">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Child Alloence</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_leave_monthly">{{ $totalChildren}}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Provident Fund</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" id="count_extra_present_monthly">{{ $totalProvidentFund }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Monthly Employee Join Report</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="employeeChart" style="height: 180px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            'use strict';

            var employeeChartCanvas = document.getElementById('employeeChart').getContext('2d');

            var employeeChartData = {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Employees',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: {!! json_encode($data) !!}
                    }
                ]
            };

            var employeeChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }]
                }
            };

            var employeeChart = new Chart(employeeChartCanvas, {
                type: 'line',
                data: employeeChartData,
                options: employeeChartOptions
            });
        });
    </script>
@endpush
@stop
