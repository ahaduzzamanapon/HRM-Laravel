@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="im im-icon-File me-2 text-primary"></i>Income Tax Deduction Certificate</h5>
            <button onclick="window.print()" class="btn btn-sm btn-outline-primary rounded-pill"><i class="im im-icon-Printer me-1"></i> Print Statement</button>
        </div>
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold mb-1">BANKING HR & PAYROLL SYSTEM</h3>
                <h5 class="text-secondary">Yearly Income Tax Deduction Certificate</h5>
                <p class="mb-0 text-muted">Assessment Year: {{ $activeYear->year_name ?? '2025-2026' }}</p>
            </div>

            <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Employee Name:</strong> {{ $user->name }} {{ $user->last_name }}</p>
                    <p class="mb-1"><strong>Employee ID:</strong> {{ $user->emp_id ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Designation:</strong> {{ optional($user->designation)->desi_name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Department / Branch:</strong> {{ optional($user->department)->name ?? 'N/A' }} / {{ optional($user->branch)->branch_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>TIN Number:</strong> <span class="font-monospace fw-bold">{{ $profile->tin_number ?? 'N/A' }}</span></p>
                    <p class="mb-1"><strong>Tax Circle:</strong> {{ $profile->tax_circle ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Tax Zone:</strong> {{ $profile->tax_zone ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Filing Status:</strong> <span class="badge bg-success">{{ $profile->filing_status ?? 'Registered' }}</span></p>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Tax Calculation Breakdown</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gross Salary & Allowances (Annual Estimate)</td>
                            <td class="text-end fw-bold">৳ {{ number_format(($profile->monthly_tax_deduction > 0 ? $profile->yearly_tax_estimate * 20 : 600000), 2) }}</td>
                        </tr>
                        <tr>
                            <td>Less: Non-taxable Allowances & Exemption Limit</td>
                            <td class="text-end text-success">(৳ 350,000.00)</td>
                        </tr>
                        <tr class="table-info fw-bold">
                            <td>Net Taxable Income</td>
                            <td class="text-end">৳ {{ number_format(max(0, ($profile->monthly_tax_deduction > 0 ? $profile->yearly_tax_estimate * 20 : 600000) - 350000), 2) }}</td>
                        </tr>
                        <tr>
                            <td>Gross Tax Payable (Based on Tax Slabs)</td>
                            <td class="text-end">৳ {{ number_format($profile->yearly_tax_estimate + $profile->rebate_claimed, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Less: Investment Rebate (15% on Approved Investments)</td>
                            <td class="text-end text-success">(৳ {{ number_format($profile->rebate_claimed, 2) }})</td>
                        </tr>
                        <tr class="table-warning fw-bold fs-5">
                            <td>Net Yearly Tax Payable</td>
                            <td class="text-end text-primary">৳ {{ number_format($profile->yearly_tax_estimate, 2) }}</td>
                        </tr>
                        <tr class="table-danger fw-bold fs-5">
                            <td>Estimated Monthly Tax Deduction (via Payroll)</td>
                            <td class="text-end text-danger">৳ {{ number_format($profile->monthly_tax_deduction, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                <div class="text-center">
                    <div class="border-bottom pb-1 px-4 mb-1">Prepared By</div>
                    <small class="text-muted">Accounts Officer</small>
                </div>
                <div class="text-center">
                    <div class="border-bottom pb-1 px-4 mb-1">Verified By</div>
                    <small class="text-muted">Manager HR & Payroll</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
