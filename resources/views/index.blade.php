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
<section class="content">
    {{-- Branch Filter Bar --}}
    <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 12px; margin-bottom: 20px;">
        <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#0177bc,#004b79);display:flex;align-items:center;justify-content:center;color:#fff;">
                        <i class="fa fa-building-o" style="font-size:18px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size:15px;">Dashboard Scope: {{ $activeBranch ? ($activeBranch->branch_name ?? $activeBranch->name) : 'All Branches (Global Overview)' }}</h6>
                        <small class="text-muted">Viewing metrics, attendance, leaves, and statistics branch-wise</small>
                    </div>
                </div>

                @if(isSuperAdmin())
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                    <label for="dashboard_branch_id" class="me-2 fw-semibold text-secondary mb-0" style="font-size:13px;white-space:nowrap;">Select Branch:</label>
                    <select name="branch_id" id="dashboard_branch_id" class="form-select form-select-sm" style="min-width: 220px;" onchange="this.form.submit()">
                        <option value="all" {{ !$selectedBranchId ? 'selected' : '' }}>All Branches (Global)</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ (string)$selectedBranchId === (string)$branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name ?? $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                @else
                <div>
                    <span class="badge bg-primary px-3 py-2" style="font-size:13px;font-weight:500;">
                        <i class="fa fa-map-marker me-1"></i> Assigned Branch: {{ auth()->user()->branch->branch_name ?? auth()->user()->branch->name ?? 'Head Office' }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

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
