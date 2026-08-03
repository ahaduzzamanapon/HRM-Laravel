@extends('layouts.default')

@section('title')
Arrear Bills Management @parent
@stop

@section('content')
<section class="content-header py-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 font-weight-bold" style="color: #2b2d42 !important;">Arrear Bill System</h3>
                <p class="mb-0" style="color: #6c757d !important;">Track monthly utility/service bills, roll over arrears, and allocate payments</p>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary btn-sm px-3 py-2 mr-2 shadow-sm" data-toggle="modal" data-target="#generateBillModal">
                    <i class="fa fa-plus-circle mr-1"></i> Generate Next Bill
                </button>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm" role="alert">
            <i class="fa fa-check-circle mr-2"></i> {!! session('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3 shadow-sm" role="alert">
            <i class="fa fa-times-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3 shadow-sm" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i> Please correct the errors in the form.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body py-3 px-4">
            <form action="{{ route('admin.pension.arrear-bills.index') }}" method="GET" class="form-inline d-flex flex-wrap align-items-center">
                <span class="mr-3 font-weight-bold" style="font-size: 0.9rem; color: #495057 !important;">
                    <i class="fa fa-filter mr-1 text-primary"></i> Filter List:
                </span>
                
                <select name="user_id" id="filter_user_id" class="form-control form-control-sm mr-2 select2-filter" style="min-width: 200px;">
                    <option value="">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ trim($user->name . ' ' . $user->last_name) }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>

                <select name="status" id="filter_status" class="form-control form-control-sm mr-2" style="width: 130px;">
                    <option value="">All Statuses</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>

                <input type="month" name="billing_period" class="form-control form-control-sm mr-2" value="{{ request('billing_period') }}" style="width: 160px;">

                <button type="submit" class="btn btn-secondary btn-sm px-3 mr-1">
                    Apply Filter
                </button>
                @if(request('user_id') || request('status') || request('billing_period'))
                    <a href="{{ route('admin.pension.arrear-bills.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="bg-light text-secondary font-weight-bold">
                        <tr>
                            <th style="color: #495057 !important;">Sl.</th>
                            <th class="text-left pl-4" style="color: #495057 !important;">Employee</th>
                            <th style="color: #495057 !important;">Billing Cycles</th>
                            <th style="color: #495057 !important;">Total Base Amount</th>
                            <th style="color: #495057 !important;">Latest Arrear</th>
                            <th style="color: #495057 !important;">Latest Total Owed</th>
                            <th style="color: #495057 !important;">Total Paid</th>
                            <th style="color: #495057 !important;">Overall Status</th>
                            <th style="color: #495057 !important;">Last Updated</th>
                            <th style="color: #495057 !important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedBills = $bills->groupBy('user_id');
                        @endphp
                        @forelse($groupedBills as $userId => $userBills)
                            @php
                                $firstBill = $userBills->first();
                                $user = $firstBill->user;
                                $sortedBills = $userBills->sortByDesc('billing_period');
                                $latestBill = $sortedBills->first();
                                
                                $totalBase = $userBills->sum('base_amount');
                                $totalPaid = $userBills->sum('paid_amount');
                                
                                // Calculate total remaining outstanding balance across all user's bills
                                $totalDue = 0;
                                foreach($userBills as $ub) {
                                    $totalDue += ((float)$ub->base_amount - (float)$ub->paid_amount);
                                }
                                if ($totalDue < 0) {
                                    $totalDue = 0;
                                }
                                
                                $latestArrear = $latestBill->arrear_amount;
                                $latestTotalOwed = $latestBill->total_amount;
                                
                                // Determine overall status based on all cycles:
                                // If all are paid -> paid.
                                // If all are unpaid -> unpaid.
                                // If mixed -> partially paid.
                                $paidCount = $userBills->where('status', 'paid')->count();
                                $unpaidCount = $userBills->where('status', 'unpaid')->count();
                                $totalCount = $userBills->count();

                                if ($paidCount === $totalCount) {
                                    $overallStatus = 'paid';
                                } elseif ($unpaidCount === $totalCount) {
                                    $overallStatus = 'unpaid';
                                } else {
                                    $overallStatus = 'partially_paid';
                                }
                                
                                $badgeStyle = 'background-color: #6c757d; color: #fff;';
                                if($overallStatus == 'paid') $badgeStyle = 'background-color: #198754; color: #fff;';
                                if($overallStatus == 'partially_paid') $badgeStyle = 'background-color: #ffc107; color: #000;';
                                if($overallStatus == 'unpaid') $badgeStyle = 'background-color: #dc3545; color: #fff;';
                            @endphp
                            <tr class="parent-row">
                                <td class="align-middle" style="color: #495057 !important;">{{ $loop->iteration + ($bills->currentPage() - 1) * $bills->perPage() }}</td>
                                <td class="align-middle text-left pl-4">
                                    <strong style="color: #2b2d42 !important;">{{ $user ? trim($user->name . ' ' . $user->last_name) : 'N/A' }}</strong><br>
                                    <small style="color: #6c757d !important;">{{ $user->email ?? '' }}</small>
                                </td>
                                <td class="align-middle">
                                    <span class="badge px-3 py-2 font-weight-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;">
                                        {{ $userBills->count() }} Cycle(s)
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold" style="color: #2b2d42 !important;">৳{{ number_format((float)$totalBase, 2) }}</td>
                                <td class="align-middle font-weight-bold" style="color: #dc3545 !important;">৳{{ number_format((float)$latestArrear, 2) }}</td>
                                <td class="align-middle font-weight-bold" style="color: #0d6efd !important;">৳{{ number_format((float)$latestTotalOwed, 2) }}</td>
                                <td class="align-middle font-weight-bold" style="color: #198754 !important;">৳{{ number_format((float)$totalPaid, 2) }}</td>
                                <td class="align-middle">
                                    <span class="badge px-3 py-2 rounded-pill font-weight-bold text-uppercase" style="{{ $badgeStyle }} font-size: 0.75rem;">
                                        {{ str_replace('_', ' ', $overallStatus) }}
                                    </span>
                                </td>
                                <td class="align-middle" style="color: #6c757d !important; font-size: 0.85rem;">
                                    {{ $latestBill->updated_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-info btn-xs px-2 py-1 mr-1 text-white font-weight-bold" 
                                                data-toggle="collapse" 
                                                data-target="#details-user-{{ $userId }}" 
                                                title="Toggle Details">
                                            <i class="fa fa-list mr-1"></i> Details
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Collapsible Row for Details -->
                            <tr id="details-user-{{ $userId }}" class="collapse bg-light">
                                <td colspan="10" class="p-3">
                                    <div class="card card-outline card-info shadow-sm mb-0">
                                        <div class="card-header py-2 bg-secondary text-white d-flex justify-content-between align-items-center" style="background-color: #0177bc !important;">
                                            <h6 class="card-title mb-0 font-weight-bold" style="font-size: 0.9rem;color: white !important;">
                                                <i class="fa fa-history mr-1"></i> Month-wise Bill Breakdown: {{ $user ? trim($user->name . ' ' . $user->last_name) : 'N/A' }}
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm table-bordered table-striped mb-0 text-center">
                                                <thead>
                                                    <tr class="bg-dark text-white" style="font-size: 0.85rem;">
                                                        <th class="py-2">Period</th>
                                                        <th class="py-2">Base Amount</th>
                                                        <th class="py-2">Arrear Rollover</th>
                                                        <th class="py-2">Total Owed</th>
                                                        <th class="py-2">Total Paid</th>
                                                        <th class="py-2">Status</th>
                                                        <th class="py-2">Last Updated</th>
                                                        <th class="py-2">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sortedBills as $bill)
                                                        @php
                                                            $bStatus = $bill->status;
                                                            $bBadgeStyle = 'background-color: #6c757d; color: #fff;';
                                                            if($bStatus == 'paid') $bBadgeStyle = 'background-color: #198754; color: #fff;';
                                                            if($bStatus == 'partially_paid') $bBadgeStyle = 'background-color: #ffc107; color: #000;';
                                                            if($bStatus == 'unpaid') $bBadgeStyle = 'background-color: #dc3545; color: #fff;';
                                                        @endphp
                                                        <tr style="font-size: 0.9rem;">
                                                            <td class="align-middle">
                                                                <span class="badge px-3 py-1 font-weight-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;">{{ $bill->billing_period }}</span>
                                                            </td>
                                                            <td class="align-middle font-weight-bold">৳{{ number_format((float)$bill->base_amount, 2) }}</td>
                                                            <td class="align-middle font-weight-bold text-danger">৳{{ number_format((float)$bill->arrear_amount, 2) }}</td>
                                                            <td class="align-middle font-weight-bold text-primary">৳{{ number_format((float)$bill->total_amount, 2) }}</td>
                                                            <td class="align-middle font-weight-bold text-success">৳{{ number_format((float)$bill->paid_amount, 2) }}</td>
                                                            <td class="align-middle">
                                                                <span class="badge px-3 py-1 rounded-pill font-weight-bold text-uppercase" style="{{ $bBadgeStyle }} font-size: 0.7rem;">
                                                                    {{ str_replace('_', ' ', $bStatus) }}
                                                                </span>
                                                            </td>
                                                            <td class="align-middle text-muted" style="font-size: 0.8rem;">
                                                                {{ $bill->updated_at->format('M d, Y h:i A') }}
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="d-flex justify-content-center align-items-center">
                                                                    @if($bill->status !== 'paid')
                                                                        <button type="button" class="btn btn-success btn-xs px-2 py-1 btn-pay-now mr-1" 
                                                                                data-toggle="modal" 
                                                                                data-target="#allocatePaymentModal" 
                                                                                data-user-id="{{ $bill->user_id }}"
                                                                                data-user-name="{{ $bill->user ? trim($bill->user->name . ' ' . $bill->user->last_name) : 'N/A' }}"
                                                                                data-amount="{{ number_format((float)($bill->base_amount - $bill->paid_amount), 2, '.', '') }}"
                                                                                data-billing-period="{{ $bill->billing_period }}"
                                                                                data-base-amount="{{ number_format((float)$bill->base_amount, 2) }}"
                                                                                data-arrear-amount="{{ number_format((float)$bill->arrear_amount, 2) }}"
                                                                                data-total-amount="{{ number_format((float)$bill->total_amount, 2) }}"
                                                                                data-paid-amount="{{ number_format((float)$bill->paid_amount, 2) }}"
                                                                                title="Allocate Payment">
                                                                            <i class="fa fa-money mr-1"></i> Pay
                                                                        </button>
                                                                    @endif

                                                                    @if($bill->status !== 'paid')
                                                                        <button type="button" class="btn btn-info btn-xs px-2 py-1 btn-edit-bill mr-1 text-white"
                                                                                data-toggle="modal"
                                                                                data-target="#editBillModal"
                                                                                data-id="{{ $bill->id }}"
                                                                                data-user-name="{{ $bill->user ? trim($bill->user->name . ' ' . $bill->user->last_name) : 'N/A' }}"
                                                                                data-base-amount="{{ $bill->base_amount }}"
                                                                                data-billing-period="{{ $bill->billing_period }}"
                                                                                title="Edit Bill">
                                                                            <i class="fa fa-edit mr-1"></i> Edit
                                                                        </button>
                                                                    @endif

                                                                    <form action="{{ route('admin.pension.arrear-bills.destroy', $bill->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bill? This action cannot be undone.')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-xs px-2 py-1" title="Delete Bill">
                                                                            <i class="fa fa-trash mr-1"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="fa fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                                    <span class="h6 font-weight-bold d-block mb-1">No Bill Records Found</span>
                                    <span class="small">Try adjusting your filters or generate a new bill above.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix bg-white border-top">
            <div class="float-right">
                {{ $bills->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Generate Bill -->
<div class="modal fade" id="generateBillModal" tabindex="-1" role="dialog" aria-labelledby="generateBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.pension.arrear-bills.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="generateBillModalLabel">Generate Next Bill</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bill_user_id" class="font-weight-bold">Select Employee <span class="text-danger">*</span></label>
                        <select name="user_id" id="bill_user_id" class="form-control select2-modal" required style="width: 100%;">
                            <option value="">-- Choose Employee --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ trim($user->name . ' ' . $user->last_name) }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold mb-0 text-primary"><i class="fa fa-calendar-check mr-1"></i> Bill Cycles to Generate</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" id="btn_add_bill_row">
                            <i class="fa fa-plus-circle mr-1"></i> Add Row
                        </button>
                    </div>

                    <div id="bill_cycles_container">
                        <div class="row bill-cycle-row mb-3 align-items-end">
                            <div class="col-sm-6">
                                <label class="font-weight-bold">Billing Period (YYYY-MM) <span class="text-danger">*</span></label>
                                <input type="month" name="billing_periods[]" class="form-control" required value="{{ date('Y-m') }}">
                            </div>
                            <div class="col-sm-5">
                                <label class="font-weight-bold">Base Bill Amount (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="base_amounts[]" class="form-control" placeholder="0.00" min="0" required>
                            </div>
                            <div class="col-sm-1 text-right">
                                <button type="button" class="btn btn-danger btn-block btn-remove-bill-row" style="margin-top: 29px; height: 38px;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted mt-2">Any existing unpaid or partially paid balance will automatically roll over as arrears for subsequent months.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Generate Bill</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Allocate Payment -->
<div class="modal fade" id="allocatePaymentModal" tabindex="-1" role="dialog" aria-labelledby="allocatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.pension.arrear-bills.allocate') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold" id="allocatePaymentModalLabel">Allocate Payment</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Employee</label>
                        <input type="text" id="pay_user_name" class="form-control" readonly style="background-color: #e9ecef;">
                        <input type="hidden" name="user_id" id="pay_user_id" required>
                    </div>

                    <!-- Bill Summary Section -->
                    <div id="bill_summary_section" style="display:none; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px; padding: 12px; margin-bottom: 15px;">
                        <h6 class="font-weight-bold mb-2" style="color: #198754;"><i class="fa fa-info-circle"></i> Bill Details to Pay</h6>
                        <div class="row small">
                            <div class="col-6 mb-1"><strong>Billing Period:</strong> <span id="summary_period" class="font-weight-bold"></span></div>
                            <div class="col-6 mb-1"><strong>Base Amount:</strong> ৳<span id="summary_base"></span></div>
                            <div class="col-6 mb-1"><strong>Arrear Rollover:</strong> ৳<span id="summary_arrear"></span></div>
                            <div class="col-6 mb-1"><strong>Total Owed:</strong> ৳<span id="summary_total"></span></div>
                            <div class="col-6"><strong>Already Paid:</strong> ৳<span id="summary_paid"></span></div>
                            <div class="col-6"><strong>Remaining Due:</strong> ৳<span id="summary_remaining" style="color: #dc3545; font-weight: bold;"></span></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="amount" class="font-weight-bold">Payment Amount (৳) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" min="0.01" required>
                        <small class="form-text text-muted">Payments will be distributed starting from the oldest unpaid bill cycle first.</small>
                        @error('amount')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4">Apply Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Edit Bill -->
<div class="modal fade" id="editBillModal" tabindex="-1" role="dialog" aria-labelledby="editBillModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editBillForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold" id="editBillModalLabel">Edit Bill</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Employee</label>
                        <input type="text" id="edit_user_name" class="form-control" readonly style="background-color: #e9ecef;">
                    </div>

                    <div class="form-group">
                        <label for="edit_billing_period" class="font-weight-bold">Billing Period (YYYY-MM) <span class="text-danger">*</span></label>
                        <input type="month" name="billing_period" id="edit_billing_period" class="form-control" required>
                        @error('billing_period')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="edit_base_amount" class="font-weight-bold">Base Bill Amount (৳) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="base_amount" id="edit_base_amount" class="form-control" required min="0">
                        @error('base_amount')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info px-4 text-white">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Global object to track active row data
        var activeBillData = {};

        // Initialize Select2 for filters and modals
        if ($.fn.select2) {
            $('.select2-filter').select2({
                placeholder: "Select Employee",
                allowClear: true
            });
            $('.select2-modal').select2({
                dropdownParent: $('#generateBillModal')
            });
        }

        // Accordion behavior: Collapse other open details when one is clicked
        $(document).on('click', '[data-toggle="collapse"]', function(e) {
            var target = $(this).attr('data-target');
            if (target && target.startsWith('#details-user-')) {
                $('.collapse').not(target).collapse('hide');
            }
        });

        // Handle clicking direct Pay button on bill rows
        $(document).on('click', '.btn-pay-now', function() {
            var $btn = $(this);
            var userId = $btn.attr('data-user-id') || $btn.data('user-id');
            var userName = $btn.attr('data-user-name') || $btn.data('user-name');
            var amount = $btn.attr('data-amount') || $btn.data('amount');
            var period = $btn.attr('data-billing-period') || $btn.data('billing-period');
            var base = $btn.attr('data-base-amount') || $btn.data('base-amount');
            var arrear = $btn.attr('data-arrear-amount') || $btn.data('arrear-amount');
            var total = $btn.attr('data-total-amount') || $btn.data('total-amount');
            var paid = $btn.attr('data-paid-amount') || $btn.data('paid-amount');

            console.log("Pay button clicked for user:", userName, "ID:", userId);

            // Populate form fields immediately
            $('#pay_user_name').val(userName);
            $('#pay_user_id').val(userId);
            $('#amount').val(amount);
            
            // Populate summary fields
            $('#summary_period').text(period);
            $('#summary_base').text(base);
            $('#summary_arrear').text(arrear);
            $('#summary_total').text(total);
            $('#summary_paid').text(paid);
            $('#summary_remaining').text(amount);

            // Show summary section
            $('#bill_summary_section').show();
        });

        // Handle clicking direct Edit button on bill rows
        $(document).on('click', '.btn-edit-bill', function() {
            var $btn = $(this);
            var id = $btn.attr('data-id') || $btn.data('id');
            var userName = $btn.attr('data-user-name') || $btn.data('user-name');
            var baseAmount = $btn.attr('data-base-amount') || $btn.data('base-amount');
            var billingPeriod = $btn.attr('data-billing-period') || $btn.data('billing-period');

            console.log("Edit button clicked for bill ID:", id);

            // Set input values immediately
            $('#edit_user_name').val(userName);
            $('#edit_base_amount').val(baseAmount);
            $('#edit_billing_period').val(billingPeriod);
            
            // Map the form action dynamically (handling both raw and url-encoded route parameters)
            var actionUrl = "{{ route('admin.pension.arrear-bills.update', ':id') }}"
                .replace('%3Aid', id)
                .replace(':id', id);
            $('#editBillForm').attr('action', actionUrl);
        });

        // Add new bill cycle row in batch creation
        $(document).on('click', '#btn_add_bill_row', function() {
            var defaultMonth = "{{ date('Y-m') }}";
            var newRow = `
                <div class="row bill-cycle-row mb-3 align-items-end">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Billing Period (YYYY-MM) <span class="text-danger">*</span></label>
                        <input type="month" name="billing_periods[]" class="form-control" required value="${defaultMonth}">
                    </div>
                    <div class="col-sm-5">
                        <label class="font-weight-bold">Base Bill Amount (৳) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="base_amounts[]" class="form-control" placeholder="0.00" min="0" required>
                    </div>
                    <div class="col-sm-1 text-right">
                        <button type="button" class="btn btn-danger btn-block btn-remove-bill-row" style="margin-top: 29px; height: 38px;"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#bill_cycles_container').append(newRow);
        });

        // Remove bill cycle row in batch creation
        $(document).on('click', '.btn-remove-bill-row', function() {
            if ($('.bill-cycle-row').length > 1) {
                $(this).closest('.bill-cycle-row').remove();
            } else {
                alert('At least one bill cycle is required.');
            }
        });
    });
</script>
@endpush
