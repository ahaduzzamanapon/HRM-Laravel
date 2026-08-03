@extends('layouts.default')

@section('title')
Pension Comprehensive Report @parent
@stop

@section('content')
<!-- Custom Pension Report Styles (Brand Header Color #0177bc context) -->
<style>
    :root {
        --pension-primary: #0177bc;
        --pension-primary-dark: #00568c;
        --pension-primary-light: #e6f4fc;
        --pension-gradient-1: linear-gradient(135deg, #0177bc 0%, #0284c7 100%);
        --pension-gradient-2: linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
        --pension-gradient-3: linear-gradient(135deg, #0369a1 0%, #4f46e5 100%);
    }

    /* Hero Header Banner */
    .pension-hero-banner {
        background: var(--pension-gradient-1);
        border-radius: 12px;
        padding: 24px 30px;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(1, 119, 188, 0.25);
        position: relative;
        overflow: hidden;
    }
    .pension-hero-banner::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .pension-hero-banner::after {
        content: "";
        position: absolute;
        bottom: -40%;
        right: 15%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Action Buttons */
    .btn-pension-hero-print {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(8px);
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 18px;
        transition: all 0.25s ease;
    }
    .btn-pension-hero-print:hover {
        background: #ffffff;
        color: var(--pension-primary) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    /* Filter Card */
    .pension-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }
    .pension-filter-card .form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.88rem;
        height: 38px;
    }
    .pension-filter-card .form-control:focus {
        border-color: var(--pension-primary);
        box-shadow: 0 0 0 3px rgba(1, 119, 188, 0.15);
    }

    .btn-pension-primary {
        background: var(--pension-primary);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-pension-primary:hover {
        background: var(--pension-primary-dark);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(1, 119, 188, 0.3);
        transform: translateY(-1px);
    }

    /* Quick Date Tags */
    .btn-quick-date {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        transition: all 0.2s ease;
    }
    .btn-quick-date:hover {
        background: var(--pension-primary-light);
        border-color: var(--pension-primary);
        color: var(--pension-primary);
    }

    /* Stat Cards */
    .pension-stat-card {
        border-radius: 14px;
        border: none;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12);
        color: #ffffff;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .pension-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.18);
    }
    .pension-stat-card.card-1 {
        background: var(--pension-gradient-1);
    }
    .pension-stat-card.card-2 {
        background: var(--pension-gradient-2);
    }
    .pension-stat-card.card-3 {
        background: var(--pension-gradient-3);
    }

    .pension-stat-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #ffffff;
    }

    .pension-stat-value {
        font-size: 1.95rem;
        font-weight: 800;
        line-height: 1.2;
        color: #ffffff !important;
        letter-spacing: -0.5px;
    }

    .pension-stat-subbox {
        background: rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(4px);
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    /* Tabs Styling */
    .pension-tabs-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    .pension-nav-tabs {
        border-bottom: 2px solid #f1f5f9;
        background: #f8fafc;
        padding: 8px 16px 0 16px;
        gap: 8px;
    }
    .pension-nav-tabs .nav-link {
        border: none !important;
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 12px 22px !important;
        border-radius: 8px 8px 0 0 !important;
        background: transparent !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    .pension-nav-tabs .nav-link:hover {
        color: var(--pension-primary) !important;
        background: rgba(1, 119, 188, 0.06) !important;
    }
    .pension-nav-tabs .nav-link.active {
        color: var(--pension-primary) !important;
        background: #ffffff !important;
        border-bottom: 3px solid var(--pension-primary) !important;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.03);
    }

    /* Table Styling */
    .pension-table {
        width: 100%;
        margin-bottom: 0;
    }
    .pension-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 14px 18px;
        border-bottom: 2px solid #e2e8f0;
        border-top: none;
    }
    .pension-table tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #334155;
    }
    .pension-table tbody tr:hover {
        background-color: rgba(1, 119, 188, 0.025);
    }

    /* Employee Avatar Circle */
    .emp-avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--pension-primary) 0%, #0284c7 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(1, 119, 188, 0.25);
    }

    /* Pill Badges */
    .status-badge-pass {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
    }
    .status-badge-fail {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
    }
    .status-badge-pending {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
    }
    .status-badge-info {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
    }
</style>

<div class="content px-3 pt-3">
    <!-- Hero Header Banner -->
    <div class="pension-hero-banner mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-white text-primary px-3 py-1 rounded-pill font-weight-bold mr-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="fa fa-shield-alt mr-1"></i> HRM PENSION MODULE
                    </span>
                    <small class="text-white-50"><i class="fa fa-calendar-alt mr-1"></i> Report Period: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</small>
                </div>
                <h2 class="mb-1 font-weight-bold text-white">Pension Reports & Analytics</h2>
                <p class="mb-0 text-white-50" style="font-size: 0.92rem;">
                    Comprehensive audit trail & summary covering Eligibility Checklists, Disbursements, and Arrear Bills
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <button onclick="window.print()" class="btn btn-pension-hero-print">
                    <i class="fa fa-print mr-2"></i> Print Official Report
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="pension-filter-card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.pension.reports.index') }}" method="GET" id="reportFilterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label for="start_date" class="font-weight-bold text-dark mb-1" style="font-size: 0.82rem;">
                                <i class="fa fa-calendar-alt text-primary mr-1"></i> Start Date
                            </label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label for="end_date" class="font-weight-bold text-dark mb-1" style="font-size: 0.82rem;">
                                <i class="fa fa-calendar-check text-primary mr-1"></i> End Date
                            </label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label for="user_id" class="font-weight-bold text-dark mb-1" style="font-size: 0.82rem;">
                                <i class="fa fa-user-tag text-primary mr-1"></i> Employee Filter
                            </label>
                            <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;">
                                <option value="">All Employees</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} {{ $user->last_name ?? '' }} ({{ $user->emp_id ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center pt-3 pt-md-0">
                        <button type="submit" class="btn btn-pension-primary flex-fill py-2 mr-2">
                            <i class="fa fa-search mr-1"></i> Generate
                        </button>
                        <a href="{{ route('admin.pension.reports.index') }}" class="btn btn-outline-secondary py-2 px-3" style="border-radius: 8px;" title="Reset Filters">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Date Ranges -->
                <div class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center">
                    <span class="mr-3 text-secondary font-weight-bold" style="font-size: 0.8rem;"><i class="fa fa-bolt text-warning mr-1"></i> Quick Ranges:</span>
                    <button type="button" class="btn btn-quick-date mr-2 mb-1" onclick="setQuickDates('this-month')">This Month</button>
                    <button type="button" class="btn btn-quick-date mr-2 mb-1" onclick="setQuickDates('last-month')">Last Month</button>
                    <button type="button" class="btn btn-quick-date mr-2 mb-1" onclick="setQuickDates('last-90')">Last 90 Days</button>
                    <button type="button" class="btn btn-quick-date mb-1" onclick="setQuickDates('this-year')">This Year</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics Dashboard -->
    <div class="row mb-4">
        <!-- Checklist Summary -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 pension-stat-card card-1 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase tracking-wider font-weight-bold" style="font-size: 0.75rem; opacity: 0.9;">Eligibility Checklists</span>
                    <div class="pension-stat-icon-wrap">
                        <i class="fa fa-clipboard-check"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="pension-stat-value">{{ $summary['eligibility_total'] }}</div>
                    <small class="text-white-50 font-weight-semibold">Checks Conducted in Period</small>
                </div>
                <div class="pension-stat-subbox mt-auto">
                    <div class="row text-center">
                        <div class="col-4 border-right border-secondary border-opacity-25">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Pass</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.95rem;">{{ $summary['eligibility_passed'] }}</span>
                        </div>
                        <div class="col-4 border-right border-secondary border-opacity-25">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Fail</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.95rem;">{{ $summary['eligibility_failed'] }}</span>
                        </div>
                        <div class="col-4">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Pending</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.95rem;">{{ $summary['eligibility_pending'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disbursement Summary -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 pension-stat-card card-2 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase tracking-wider font-weight-bold" style="font-size: 0.75rem; opacity: 0.9;">Disbursement Total</span>
                    <div class="pension-stat-icon-wrap">
                        <i class="fa fa-wallet"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="pension-stat-value">৳{{ number_format((float)$summary['disbursement_total_amount'], 2) }}</div>
                    <small class="text-white-50 font-weight-semibold">Net Payable Disbursements</small>
                </div>
                <div class="pension-stat-subbox mt-auto">
                    <div class="row text-center">
                        <div class="col-6 border-right border-secondary border-opacity-25">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Paid ({{ $disbursements->where('status', 'Paid')->count() }})</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.9rem;">৳{{ number_format((float)$summary['disbursement_paid_amount'], 2) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Pending ({{ $disbursements->where('status', 'Pending')->count() }})</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.9rem;">৳{{ number_format((float)$summary['disbursement_pending_amount'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arrear Bill Summary -->
        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card h-100 pension-stat-card card-3 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase tracking-wider font-weight-bold" style="font-size: 0.75rem; opacity: 0.9;">Arrear Billing System</span>
                    <div class="pension-stat-icon-wrap">
                        <i class="fa fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="pension-stat-value">৳{{ number_format((float)$summary['bills_total_amount'], 2) }}</div>
                    <small class="text-white-50 font-weight-semibold">Total Owed ({{ $summary['bills_total_count'] }} Bills)</small>
                </div>
                <div class="pension-stat-subbox mt-auto">
                    <div class="row text-center">
                        <div class="col-6 border-right border-secondary border-opacity-25">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Paid Amount</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.9rem;">৳{{ number_format((float)$summary['bills_paid_amount'], 2) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">Outstanding Due</small>
                            <span class="font-weight-bold text-white" style="font-size: 0.9rem;">৳{{ number_format((float)$summary['bills_due_amount'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Details Tables (Tabs) -->
    <div class="pension-tabs-card mb-5">
        <ul class="nav pension-nav-tabs" id="pensionReportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="checklist-tab" data-toggle="tab" href="#checklist" role="tab" aria-controls="checklist" aria-selected="true">
                    <i class="fa fa-tasks mr-2 text-primary"></i> Eligibility Checklist 
                    <span class="badge badge-pill badge-primary ml-2 px-2.5 py-1">{{ $eligibilityChecks->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="disbursements-tab" data-toggle="tab" href="#disbursements" role="tab" aria-controls="disbursements" aria-selected="false">
                    <i class="fa fa-credit-card mr-2 text-info"></i> Disbursement Records
                    <span class="badge badge-pill badge-info ml-2 px-2.5 py-1">{{ $disbursements->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="arrears-tab" data-toggle="tab" href="#arrears" role="tab" aria-controls="arrears" aria-selected="false">
                    <i class="fa fa-file-invoice-dollar mr-2 text-warning"></i> Arrear Bills
                    <span class="badge badge-pill badge-warning ml-2 px-2.5 py-1">{{ $bills->count() }}</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="pensionReportTabsContent">
            <!-- Checklist Tab -->
            <div class="tab-pane fade show active" id="checklist" role="tabpanel" aria-labelledby="checklist-tab">
                <div class="table-responsive">
                    <table class="table pension-table text-center mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="text-left">Employee</th>
                                <th>Age Check</th>
                                <th>Service Years</th>
                                <th>Documents</th>
                                <th>Disciplinary</th>
                                <th>Overall Status</th>
                                <th>Checked By</th>
                                <th>Checked At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eligibilityChecks as $check)
                            <tr>
                                <td class="font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                                <td class="text-left">
                                    <div class="d-flex align-items-center">
                                        <div class="emp-avatar-circle mr-2.5">
                                            {{ strtoupper(substr($check->profile->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $check->profile->user->name ?? 'N/A' }} {{ $check->profile->user->last_name ?? '' }}
                                            </div>
                                            <small class="text-muted"><i class="fa fa-id-card mr-1"></i>{{ $check->profile->user->emp_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($check->age_validated)
                                        <span class="status-badge-pass"><i class="fa fa-check-circle mr-1"></i>Valid</span>
                                    @else
                                        <span class="status-badge-fail"><i class="fa fa-times-circle mr-1"></i>Invalid</span>
                                    @endif
                                </td>
                                <td>
                                    @if($check->service_years_validated)
                                        <span class="status-badge-pass"><i class="fa fa-check-circle mr-1"></i>Valid</span>
                                    @else
                                        <span class="status-badge-fail"><i class="fa fa-times-circle mr-1"></i>Invalid</span>
                                    @endif
                                </td>
                                <td>
                                    @if($check->documents_verified)
                                        <span class="status-badge-pass"><i class="fa fa-shield-alt mr-1"></i>Verified</span>
                                    @else
                                        <span class="status-badge-pending"><i class="fa fa-hourglass-half mr-1"></i>Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($check->no_disciplinary_cases)
                                        <span class="status-badge-pass"><i class="fa fa-user-shield mr-1"></i>Clean</span>
                                    @else
                                        <span class="status-badge-fail"><i class="fa fa-exclamation-triangle mr-1"></i>Issues</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeStyle = 'status-badge-info';
                                        if($check->overall_status == 'Pass') $badgeStyle = 'status-badge-pass';
                                        if($check->overall_status == 'Fail') $badgeStyle = 'status-badge-fail';
                                        if($check->overall_status == 'Pending') $badgeStyle = 'status-badge-pending';
                                    @endphp
                                    <span class="{{ $badgeStyle }} px-3 py-1.5 font-weight-bold" style="font-size: 0.82rem;">
                                        {{ $check->overall_status }}
                                    </span>
                                </td>
                                <td class="text-muted"><i class="fa fa-user-check mr-1 text-secondary"></i>{{ $check->checkedBy->name ?? 'System' }}</td>
                                <td class="text-muted">
                                    @if($check->checked_at)
                                        {{ $check->checked_at->format('d M Y, h:i A') }}
                                    @else
                                        {{ $check->created_at->format('d M Y, h:i A') }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fa fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                                        <h6 class="text-secondary font-weight-bold">No eligibility checks record found</h6>
                                        <p class="text-muted small mb-0">Try adjusting your date range or employee filter above.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Disbursements Tab -->
            <div class="tab-pane fade" id="disbursements" role="tabpanel" aria-labelledby="disbursements-tab">
                <div class="table-responsive">
                    <table class="table pension-table text-center mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="text-left">Employee</th>
                                <th>Month</th>
                                <th>Method</th>
                                <th>Bank Reference</th>
                                <th>Gross</th>
                                <th>Deductions</th>
                                <th>Arrear</th>
                                <th>Net Payable</th>
                                <th>Status</th>
                                <th>Paid At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disbursements as $disbursement)
                            <tr>
                                <td class="font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                                <td class="text-left">
                                    <div class="d-flex align-items-center">
                                        <div class="emp-avatar-circle mr-2.5">
                                            {{ strtoupper(substr($disbursement->profile->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $disbursement->profile->user->name ?? 'N/A' }} {{ $disbursement->profile->user->last_name ?? '' }}
                                            </div>
                                            <small class="text-muted"><i class="fa fa-id-card mr-1"></i>{{ $disbursement->profile->user->emp_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-weight-bold text-dark">{{ $disbursement->disbursement_month }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1 font-weight-bold text-uppercase" style="border-radius: 6px; font-size: 0.75rem;">
                                        {{ $disbursement->payment_method }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $disbursement->bank_reference ?? 'N/A' }}</td>
                                <td class="font-weight-semibold">৳{{ number_format((float)$disbursement->amount, 2) }}</td>
                                <td class="text-danger font-weight-semibold">৳{{ number_format((float)$disbursement->deductions, 2) }}</td>
                                <td class="text-success font-weight-semibold">৳{{ number_format((float)$disbursement->arrear_amount, 2) }}</td>
                                <td class="font-weight-bold text-primary" style="font-size: 0.95rem;">৳{{ number_format((float)$disbursement->net_payable, 2) }}</td>
                                <td>
                                    @php
                                        $statusClass = 'status-badge-pending';
                                        if($disbursement->status == 'Paid') $statusClass = 'status-badge-pass';
                                        if($disbursement->status == 'Processing') $statusClass = 'status-badge-info';
                                        if($disbursement->status == 'Failed') $statusClass = 'status-badge-fail';
                                    @endphp
                                    <span class="{{ $statusClass }} px-3 py-1 font-weight-bold">
                                        {{ $disbursement->status }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    @if($disbursement->paid_at)
                                        {{ $disbursement->paid_at->format('d M Y') }}
                                    @else
                                        <span class="text-muted opacity-50">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fa fa-wallet fa-3x text-muted mb-3 opacity-50"></i>
                                        <h6 class="text-secondary font-weight-bold">No disbursement records found</h6>
                                        <p class="text-muted small mb-0">Try adjusting your date range or employee filter above.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Arrears Tab -->
            <div class="tab-pane fade" id="arrears" role="tabpanel" aria-labelledby="arrears-tab">
                <div class="table-responsive">
                    <table class="table pension-table text-center mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="text-left">Employee</th>
                                <th>Billing Period</th>
                                <th>Base Amount</th>
                                <th>Arrear Amount</th>
                                <th>Total Owed</th>
                                <th>Paid Amount</th>
                                <th>Outstanding Due</th>
                                <th>Status</th>
                                <th>Generated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                            <tr>
                                <td class="font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                                <td class="text-left">
                                    <div class="d-flex align-items-center">
                                        <div class="emp-avatar-circle mr-2.5">
                                            {{ strtoupper(substr($bill->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $bill->user->name ?? 'N/A' }} {{ $bill->user->last_name ?? '' }}
                                            </div>
                                            <small class="text-muted"><i class="fa fa-id-card mr-1"></i>{{ $bill->user->emp_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-weight-bold text-dark">{{ $bill->billing_period }}</td>
                                <td class="font-weight-semibold">৳{{ number_format((float)$bill->base_amount, 2) }}</td>
                                <td class="text-warning font-weight-semibold">৳{{ number_format((float)$bill->arrear_amount, 2) }}</td>
                                <td class="font-weight-bold text-dark" style="font-size: 0.92rem;">৳{{ number_format((float)$bill->total_amount, 2) }}</td>
                                <td class="text-success font-weight-semibold">৳{{ number_format((float)$bill->paid_amount, 2) }}</td>
                                <td class="font-weight-bold text-danger" style="font-size: 0.92rem;">৳{{ number_format((float)($bill->total_amount - $bill->paid_amount), 2) }}</td>
                                <td>
                                    @php
                                        $billBadge = 'status-badge-fail';
                                        if($bill->status == 'paid') $billBadge = 'status-badge-pass';
                                        if($bill->status == 'partially_paid') $billBadge = 'status-badge-pending';
                                    @endphp
                                    <span class="{{ $billBadge }} px-3 py-1 font-weight-bold text-capitalize">
                                        {{ str_replace('_', ' ', $bill->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $bill->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fa fa-file-invoice-dollar fa-3x text-muted mb-3 opacity-50"></i>
                                        <h6 class="text-secondary font-weight-bold">No arrear bills found</h6>
                                        <p class="text-muted small mb-0">Try adjusting your date range or employee filter above.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('.select2').length) {
            $('.select2').select2({
                placeholder: 'All Employees',
                allowClear: true
            });
        }
    });

    function setQuickDates(range) {
        var startInput = document.getElementById('start_date');
        var endInput = document.getElementById('end_date');
        var today = new Date();

        if (range === 'this-month') {
            var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            startInput.value = formatDate(firstDay);
            endInput.value = formatDate(lastDay);
        } else if (range === 'last-month') {
            var firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            var lastDay = new Date(today.getFullYear(), today.getMonth(), 0);
            startInput.value = formatDate(firstDay);
            endInput.value = formatDate(lastDay);
        } else if (range === 'last-90') {
            var pastDate = new Date();
            pastDate.setDate(today.getDate() - 90);
            startInput.value = formatDate(pastDate);
            endInput.value = formatDate(today);
        } else if (range === 'this-year') {
            var firstDay = new Date(today.getFullYear(), 0, 1);
            var lastDay = new Date(today.getFullYear(), 11, 31);
            startInput.value = formatDate(firstDay);
            endInput.value = formatDate(lastDay);
        }

        // Auto submit the form
        document.getElementById('reportFilterForm').submit();
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
</script>
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .content, .content * {
            visibility: visible;
        }
        .content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        #reportFilterForm, .pension-hero-banner .btn-pension-hero-print, .pension-nav-tabs {
            display: none !important;
        }
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            page-break-after: always;
        }
        .pension-stat-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            background: #ffffff !important;
            color: #000000 !important;
        }
        .pension-stat-value, .pension-stat-card * {
            color: #000000 !important;
        }
    }
</style>
@endpush
@endsection
