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
        @include('dashboard.daily_attendance')

        {{-- Monthly Attendance --}}
        @include('dashboard.monthly_attendance')
        <!-- ./col -->
    </div>

    <div class="row">
        {{-- office info  --}}
        @include('dashboard.office_info')
        {{-- Leave info  --}}
        @include('dashboard.leave_info')
    </div>

    <div class="row">
        {{-- Payroll Info --}}
        @include('dashboard.payroll_info')

        {{-- allowance & Provident Fund info --}}

        @include('dashboard.allow_provident_fund_info')
    </div>


    <div class="row">
        @include('dashboard.new_join_info')
    </div>
</section>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            'use strict';

            const ctx = document.getElementById('employeeChart').getContext('2d');

            const employeeChartData = {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Employees',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: 3,
                        pointBackgroundColor: '#3b8bba',
                        pointBorderColor: 'rgba(60,141,188,1)',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(60,141,188,1)',
                        data: {!! json_encode($data) !!},
                        fill: false,
                        tension: 0.3
                    }
                ]
            };

            const employeeChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#333'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#333',
                            beginAtZero: true
                        }
                    }
                }
            };

            new Chart(ctx, {
                type: 'line',
                data: employeeChartData,
                options: employeeChartOptions
            });
        });
    </script>
@endpush
@stop
