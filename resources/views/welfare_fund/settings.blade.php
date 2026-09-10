@extends('layouts.default')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 text-dark fw-bold">Welfare Fund Configuration</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('welfare.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="im im-icon-Back me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @include('flash::message')

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('welfare.settings.update') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- General Settings -->
                    <div class="col-12">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="im im-icon-Sliders text-primary me-2"></i>General Fund Settings</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="welfare_fund_enabled" value="1" id="welfare_fund_enabled" {{ $settings->welfare_fund_enabled ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="welfare_fund_enabled">Enable Welfare Fund System System-wide</label>
                        </div>
                    </div>

                    <!-- Deduction Policy -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Employee Deduction Type</label>
                        <select name="deduction_type" class="form-select">
                            <option value="fixed" {{ $settings->deduction_type == 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
                            <option value="percentage" {{ $settings->deduction_type == 'percentage' ? 'selected' : '' }}>Percentage of Basic Salary (%)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Deduction Policy</label>
                        <select name="deduction_policy" class="form-select">
                            <option value="compulsory" {{ $settings->deduction_policy == 'compulsory' ? 'selected' : '' }}>Compulsory for All Employees</option>
                            <option value="optional" {{ $settings->deduction_policy == 'optional' ? 'selected' : '' }}>Optional (Only if set > 0)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" name="require_attachment" value="1" id="require_attachment" {{ $settings->require_attachment ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="require_attachment">Require Document Attachment for Applications</label>
                        </div>
                    </div>

                    <!-- Company Contribution Settings -->
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="im im-icon-Building text-success me-2"></i>Organization Matching Contribution</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="company_contribution_enabled" value="1" id="company_contribution_enabled" {{ $settings->company_contribution_enabled ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="company_contribution_enabled">Enable Organization Matching Contribution</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Company Contribution Type</label>
                        <select name="company_contribution_type" class="form-select">
                            <option value="matching_percentage" {{ $settings->company_contribution_type == 'matching_percentage' ? 'selected' : '' }}>Matching Percentage of Employee Deduction (%)</option>
                            <option value="fixed" {{ $settings->company_contribution_type == 'fixed' ? 'selected' : '' }}>Fixed Monthly Amount (৳)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Company Contribution Amount / Matching %</label>
                        <input type="number" step="0.01" name="company_contribution_amount" value="{{ $settings->company_contribution_amount }}" class="form-control" placeholder="100.00">
                        <small class="text-muted">e.g. 100 means 100% matching of employee contribution.</small>
                    </div>

                    <!-- Support Claim Limits -->
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="im im-icon-Shield text-info me-2"></i>Maximum Claim Limits (৳)</h5>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Max Medical Support Limit (৳)</label>
                        <input type="number" step="0.01" name="max_medical_limit" value="{{ $settings->max_medical_limit }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Max Funeral Support Limit (৳)</label>
                        <input type="number" step="0.01" name="max_funeral_limit" value="{{ $settings->max_funeral_limit }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Max Children Education Limit (৳)</label>
                        <input type="number" step="0.01" name="max_education_limit" value="{{ $settings->max_education_limit }}" class="form-control">
                    </div>

                    <!-- Accounting & Overdraft Policy -->
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="im im-icon-Bank text-warning me-2"></i>Accounting & Overdraft Protection</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="allow_negative_balance" value="1" id="allow_negative_balance" {{ $settings->allow_negative_balance ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="allow_negative_balance">Allow Fund Overdraft (Disbursement when balance is insufficient)</label>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Save Configuration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
