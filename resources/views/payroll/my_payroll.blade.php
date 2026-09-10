@extends('layouts.default')

@section('title')
    My Payroll & Salary @parent
@stop

@section('content')
<style>
    /* Custom Square Light Tab Bar */
    .payroll-nav-bar {
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important; /* Square container */
        padding: 5px !important;       /* Reduced container padding */
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
        margin-bottom: 20px !important;
        list-style: none !important;
    }
    .payroll-nav-bar .nav-item {
        margin: 0 !important;
    }
    .payroll-tab-link {
        border-radius: 3px !important; /* Square shape tabs */
        padding: 7px 18px !important;  /* Reduced padding */
        font-weight: 600 !important;
        font-size: 14px !important;
        color: #334155 !important;    /* Clear, crisp dark text */
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        transition: all 0.15s ease-in-out !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        text-decoration: none !important;
    }
    .payroll-tab-link:hover {
        color: #0177bc !important;
        background-color: #e2e8f0 !important;
        border-color: #94a3b8 !important;
    }
    .payroll-tab-link.active {
        background-color: #0177bc !important; /* Distinct blue active tab */
        color: #ffffff !important;            /* High contrast white text */
        border-color: #0177bc !important;
        box-shadow: 0 2px 5px rgba(1, 119, 188, 0.3) !important;
    }

    .payroll-card {
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .payroll-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }
    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .badge-status-paid {
        background-color: #28a745;
        color: #fff;
    }
    .badge-status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-status-approved {
        background-color: #17a2b8;
        color: #fff;
    }
</style>

<section class="content-header">
    <h1>
        My Payroll Portal
        <small>Salary Sheets, Payslips, Tax & Bonus Records</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('/') }}">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
        <li class="active">My Payroll</li>
    </ol>
</section>

<section class="content">
    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="card payroll-card p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="metric-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fa fa-money"></i>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-bold fs-12">Basic Salary</span>
                        <h4 class="mb-0 fw-bold">৳ {{ number_format($user->basic_salary ?: 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="card payroll-card p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="metric-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="fa fa-line-chart"></i>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-bold fs-12">Gross Salary</span>
                        <h4 class="mb-0 fw-bold">৳ {{ number_format($user->gross_salary ?: 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="card payroll-card p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="metric-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-bold fs-12">Latest Net Payable</span>
                        <h4 class="mb-0 fw-bold">৳ {{ number_format($latestPayroll->net_salary ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="card payroll-card p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="metric-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fa fa-gift"></i>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-bold fs-12">Total Bonus Records</span>
                        <h4 class="mb-0 fw-bold">{{ count($bonuses) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="card payroll-card bg-white p-3">
        <ul class="payroll-nav-bar mb-3" id="payrollTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="payroll-tab-link active" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary-pane" data-toggle="tab" data-target="#salary-pane" type="button" role="tab">
                    <i class="fa fa-file-text-o me-2"></i> Salary Sheets & Payslips
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="payroll-tab-link" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax-pane" data-toggle="tab" data-target="#tax-pane" type="button" role="tab">
                    <i class="fa fa-calculator me-2"></i> Tax Statement & Deductions
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="payroll-tab-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus-pane" data-toggle="tab" data-target="#bonus-pane" type="button" role="tab">
                    <i class="fa fa-trophy me-2"></i> Bonus Records
                </button>
            </li>
        </ul>

        <div class="tab-content" id="payrollTabsContent">
            {{-- TAB 1: Salary Sheets & Payslips --}}
            <div class="tab-pane fade show active" id="salary-pane" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Processed Monthly Salary History</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Salary Month</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Gross Salary</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Payment Type</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary p-2">
                                            {{ date('F Y', strtotime($item->salary_month)) }}
                                        </span>
                                    </td>
                                    <td>৳ {{ number_format($item->b_salary, 2) }}</td>
                                    <td>৳ {{ number_format($item->total_allow, 2) }}</td>
                                    <td>৳ {{ number_format($item->gross_salary, 2) }}</td>
                                    <td class="text-danger">-৳ {{ number_format($item->total_deduct, 2) }}</td>
                                    <td class="fw-bold text-success">৳ {{ number_format($item->net_salary, 2) }}</td>
                                    <td><span class="badge bg-info text-capitalize">{{ $item->pay_type }}</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1 view-payslip-btn" data-month="{{ date('Y-m', strtotime($item->salary_month)) }}">
                                            <i class="fa fa-eye"></i> Pay Slip
                                        </button>
                                        <button class="btn btn-sm btn-outline-info view-sheet-btn" data-month="{{ date('Y-m', strtotime($item->salary_month)) }}">
                                            <i class="fa fa-file-pdf-o"></i> Salary Sheet
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa fa-folder-open-o fa-2x d-block mb-2"></i>
                                        No processed salary records found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: Tax Statement & Deductions --}}
            <div class="tab-pane fade" id="tax-pane" role="tabpanel">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card border p-3 mb-3 bg-light">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Employee Tax Profile</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>TIN Number:</th>
                                    <td>{{ $taxProfile->tin_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tax Circle / Zone:</th>
                                    <td>{{ ($taxProfile->tax_circle ?? 'N/A') . ' / ' . ($taxProfile->tax_zone ?? 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <th>Filing Status:</th>
                                    <td><span class="badge bg-primary">{{ $taxProfile->filing_status ?? 'Registered' }}</span></td>
                                </tr>
                                <tr>
                                    <th>Claimed Investment:</th>
                                    <td>৳ {{ number_format($taxProfile->investment_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Rebate Claimed (15%):</th>
                                    <td>৳ {{ number_format($taxProfile->rebate_claimed ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Estimated Yearly Tax:</th>
                                    <td class="fw-bold text-danger">৳ {{ number_format($taxProfile->yearly_tax_estimate ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Est. Monthly Deduction:</th>
                                    <td class="fw-bold">৳ {{ number_format($taxProfile->monthly_tax_deduction ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Monthly Tax Deduction Records</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Month</th>
                                        <th>Gross Salary</th>
                                        <th>Tax Deducted</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taxDeductions as $taxItem)
                                        <tr>
                                            <td>{{ date('F Y', strtotime($taxItem->salary_month)) }}</td>
                                            <td>৳ {{ number_format($taxItem->gross_salary, 2) }}</td>
                                            <td class="fw-bold text-danger">৳ {{ number_format($taxItem->tax_deduct, 2) }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-danger view-tax-btn" data-month="{{ date('Y-m', strtotime($taxItem->salary_month)) }}">
                                                    <i class="fa fa-file-text"></i> Tax Certificate
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No monthly tax deductions recorded yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: Bonus Records --}}
            <div class="tab-pane fade" id="bonus-pane" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">My Disbursed & Pending Bonuses</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Bonus Occasion / Title</th>
                                <th>Bonus Month</th>
                                <th>Base Amount</th>
                                <th>Bonus Amount</th>
                                <th>Payment Status</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bonuses as $bIndex => $bonus)
                                <tr>
                                    <td>{{ $bIndex + 1 }}</td>
                                    <td class="fw-bold">{{ $bonus->bonusSetting->title ?? 'Bonus' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ date('F Y', strtotime($bonus->bonus_month)) }}
                                        </span>
                                    </td>
                                    <td>৳ {{ number_format($bonus->base_amount, 2) }}</td>
                                    <td class="fw-bold text-success">৳ {{ number_format($bonus->bonus_amount, 2) }}</td>
                                    <td>
                                        @if($bonus->payment_status === 'paid')
                                            <span class="badge badge-status-paid">Paid</span>
                                        @elseif($bonus->payment_status === 'approved')
                                            <span class="badge badge-status-approved">Approved</span>
                                        @else
                                            <span class="badge badge-status-pending">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $bonus->payment_date ? date('d M, Y', strtotime($bonus->payment_date)) : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fa fa-gift fa-2x d-block mb-2"></i>
                                        No bonus records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function () {
        // Tab switching handler
        $('.payroll-tab-link').on('click', function (e) {
            e.preventDefault();
            $('.payroll-tab-link').removeClass('active');
            $(this).addClass('active');

            var target = $(this).attr('data-bs-target') || $(this).attr('data-target');
            if (target) {
                $('.tab-pane').removeClass('show active').css('display', 'none');
                $(target).addClass('show active').css('display', 'block');
            }
        });

        // Payslip Popup
        $('.view-payslip-btn').on('click', function () {
            var salaryMonth = $(this).data('month');
            $.ajax({
                url: '{{ route("my-payroll.payslip") }}',
                type: 'POST',
                data: {
                    salary_month: salaryMonth,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                    popupWindow.document.write(response);
                    popupWindow.focus();
                },
                error: function(xhr, status, error) {
                    alert('Could not load payslip: ' + error);
                }
            });
        });

        // Salary Sheet Popup
        $('.view-sheet-btn').on('click', function () {
            var salaryMonth = $(this).data('month');
            $.ajax({
                url: '{{ route("my-payroll.salarySheet") }}',
                type: 'POST',
                data: {
                    salary_month: salaryMonth,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                    popupWindow.document.write(response);
                    popupWindow.focus();
                },
                error: function(xhr, status, error) {
                    alert('Could not load salary sheet: ' + error);
                }
            });
        });

        // Tax Report Popup
        $('.view-tax-btn').on('click', function () {
            var salaryMonth = $(this).data('month');
            $.ajax({
                url: '{{ route("my-payroll.tax") }}',
                type: 'POST',
                data: {
                    salary_month: salaryMonth,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                    popupWindow.document.write(response);
                    popupWindow.focus();
                },
                error: function(xhr, status, error) {
                    alert('Could not load tax certificate: ' + error);
                }
            });
        });
    });
</script>
@endpush
@stop
