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
        <!-- Leave Info Section -->
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-12">Leave Info</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="My Leave Applications"
                             data-value="{{ $totalLeaveApplications }}"
                             data-icon="fa-sign-out"
                             data-description="Total number of leave applications you have submitted."
                             data-url="{{ $leaveUrl }}"
                             data-color="#00c0ef">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Leave Applications</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $totalLeaveApplications }}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="Pending Leave"
                             data-value="{{ $pendingLeaveApplications }}"
                             data-icon="fa-user-plus"
                             data-description="Number of leave applications currently awaiting approval."
                             data-url="{{ $leaveUrl }}"
                             data-color="#f39c12">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Pending Leave</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $pendingLeaveApplications }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="Approved Leave"
                             data-value="{{ $approvedLeaveApplications }}"
                             data-icon="fa-clock-o"
                             data-description="Number of your leave applications that have been officially approved."
                             data-url="{{ $leaveUrl }}"
                             data-color="#00a65a">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Approved Leave</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $approvedLeaveApplications }}</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="Rejected Leave"
                             data-value="{{ $rejectedLeaveApplications }}"
                             data-icon="fa-handshake-o"
                             data-description="Number of leave applications that were rejected."
                             data-url="{{ $leaveUrl }}"
                             data-color="#dd4b39">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">Rejected Leave</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $rejectedLeaveApplications }}</h3>
                                <i class="fa fa-handshake-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loans & Salary Info Section -->
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-12">Loans & Salary Info</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="My Loans"
                             data-value="{{ $totalLoans }}"
                             data-icon="fa-money"
                             data-description="Total loan records registered under your employee profile."
                             data-url="{{ $loanUrl }}"
                             data-color="#00c0ef">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Loans</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $totalLoans }}</h3>
                                <i class="fa fa-money col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="My Pending Loans"
                             data-value="{{ $pendingLoans }}"
                             data-icon="fa-clock-o"
                             data-description="Number of loan requests currently pending administrative review."
                             data-url="{{ $loanUrl }}"
                             data-color="#f39c12">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Pending Loans</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $pendingLoans }}</h3>
                                <i class="fa fa-clock-o col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="My Salary Grade"
                             data-value="{{ $mySalaryGrade }}"
                             data-icon="fa-line-chart"
                             data-description="Your current assigned salary scale grade level in the system."
                             data-url="{{ $profileUrl }}"
                             data-color="#605ca8">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Salary Grade</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6" style="font-size: 20px !important;">{{ $mySalaryGrade }}</h3>
                                <i class="fa fa-line-chart col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Allowance & Provident Fund Section -->
        <div class="col-md-6">
            <div class="d_card" style="background: aliceblue;">
                <div class="row" style="display: flex;flex-direction: row;align-items: center;">
                    <h4 class="col-md-12">Allowance & Provident Fund</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="My Provident Fund"
                             data-value="{{ is_numeric($myProvidentFund) ? number_format($myProvidentFund, 2) : $myProvidentFund }}"
                             data-icon="fa-user-plus"
                             data-description="Total accumulated Provident Fund balance (Employee + Employer contributions)."
                             data-url="{{ $providentFundUrl }}"
                             data-color="#0073b7">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Provident Fund</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $myProvidentFund }}</h3>
                                <i class="fa fa-user-plus col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="c_card" style="cursor: pointer;" onclick="openCardModal(this)"
                             data-title="My Children for Allowance"
                             data-value="{{ $myChildren }}"
                             data-icon="fa-sign-out"
                             data-description="Total registered dependents eligible for education allowance support."
                             data-url="{{ $childAllowanceUrl }}"
                             data-color="#ff851b">
                            <div class="col-md-12 p-0">
                                <h6 class="col-md-12 p-0" style="font-size: 15px !important;">My Children for Allowance</h6>
                            </div>
                            <div class="col-md-12 card_flex">
                                <h3 class="count-all-employees col-md-6">{{ $myChildren }}</h3>
                                <i class="fa fa-sign-out col-md-6 fa-3x" style="height: -webkit-fill-available;text-align: -webkit-center;margin: 6px -3px;" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Employee Dashboard Card Detail Pop-up Modal -->
<div class="modal fade" id="employeeCardModal" tabindex="-1" role="dialog" aria-labelledby="employeeCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white" id="cardModalHeader" style="background: #0177bc; padding: 15px 20px;">
                <h5 class="modal-title font-weight-bold d-flex align-items-center" id="employeeCardModalLabel" style="font-size: 18px; margin: 0; color: #fff;">
                    <i id="cardModalIcon" class="fa fa-info-circle" style="margin-right: 10px; font-size: 22px;"></i>
                    <span id="cardModalTitle">Card Details</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; font-size: 24px; border: none; background: transparent; cursor: pointer; color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="py-2">
                    <div id="cardModalBadge" class="badge mb-3" style="font-size: 14px; padding: 8px 16px; border-radius: 20px; display: inline-block;">
                        Summary Metric
                    </div>
                    <h2 id="cardModalValue" class="font-weight-bold my-2" style="font-size: 42px; color: #333; margin: 15px 0;">0</h2>
                    <p id="cardModalDescription" class="text-muted" style="font-size: 14px; line-height: 1.6; color: #666; max-width: 80%; margin: 0 auto;">
                        Detailed description of the metric goes here.
                    </p>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between p-3" style="background-color: #f8f9fa; border-top: 1px solid #eee;">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 6px;">Close</button>
                <a href="#" id="cardModalActionBtn" class="btn btn-primary px-4" style="border-radius: 6px; background-color: #0177bc; border-color: #0177bc; color: #fff;">
                    More Details <i class="fa fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCardModal(element) {
        var title = element.getAttribute('data-title') || 'Card Details';
        var value = element.getAttribute('data-value') || '0';
        var icon = element.getAttribute('data-icon') || 'fa-info-circle';
        var description = element.getAttribute('data-description') || '';
        var url = element.getAttribute('data-url') || '#';
        var color = element.getAttribute('data-color') || '#0177bc';

        document.getElementById('cardModalTitle').innerText = title;
        document.getElementById('cardModalValue').innerText = value;
        document.getElementById('cardModalIcon').className = 'fa ' + icon;
        document.getElementById('cardModalDescription').innerText = description;
        document.getElementById('cardModalHeader').style.backgroundColor = color;
        
        var badge = document.getElementById('cardModalBadge');
        badge.innerText = title;
        badge.style.backgroundColor = color + '22';
        badge.style.color = color;
        badge.style.border = '1px solid ' + color + '44';

        var actionBtn = document.getElementById('cardModalActionBtn');
        actionBtn.href = url;
        actionBtn.style.backgroundColor = color;
        actionBtn.style.borderColor = color;

        if (typeof $ !== 'undefined' && typeof $('#employeeCardModal').modal === 'function') {
            $('#employeeCardModal').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var bsModal = new bootstrap.Modal(document.getElementById('employeeCardModal'));
            bsModal.show();
        } else {
            $('#employeeCardModal').addClass('in show').show();
        }
    }
</script>
@endpush
@stop
