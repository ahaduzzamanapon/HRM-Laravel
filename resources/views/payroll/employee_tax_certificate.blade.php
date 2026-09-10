<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Tax Deduction Certificate - {{ $user->name }} {{ $user->last_name }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #212529;
            margin: 0;
            padding: 20px 0;
        }
        .cert-container {
            width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #0177bc;
            border-radius: 4px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
        }
        .bank-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0177bc;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .bank-logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .bank-logo {
            max-width: 65px;
            height: auto;
            object-fit: contain;
        }
        .bank-title h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #0177bc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .bank-title p {
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        .cert-ref-block {
            text-align: right;
            font-size: 12px;
            color: #444;
        }
        .cert-title-box {
            text-align: center;
            margin-bottom: 25px;
            background: #f0f7fc;
            border: 1px solid #b8daff;
            padding: 12px;
            border-radius: 4px;
        }
        .cert-title-box h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #004085;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cert-title-box p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #004085;
            font-weight: 500;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 12px;
        }
        .info-grid td {
            padding: 7px 12px;
            border: 1px solid #dcdcdc;
        }
        .info-grid td.label-cell {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
            width: 20%;
        }
        .info-grid td.value-cell {
            width: 30%;
            color: #111;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 12px;
        }
        .table-custom th {
            background-color: #0177bc;
            color: #ffffff;
            font-weight: 600;
            padding: 8px 12px;
            border: 1px solid #0177bc;
            text-align: left;
        }
        .table-custom td {
            padding: 8px 12px;
            border: 1px solid #dcdcdc;
        }
        .table-custom tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .declaration-box {
            background-color: #f9fbfd;
            border-left: 4px solid #0177bc;
            border-top: 1px solid #e3e8ee;
            border-right: 1px solid #e3e8ee;
            border-bottom: 1px solid #e3e8ee;
            padding: 15px;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 35px;
            border-radius: 0 4px 4px 0;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
            padding-top: 15px;
        }
        .sig-block {
            text-align: center;
            width: 200px;
        }
        .sig-line {
            border-top: 1px solid #333;
            margin-bottom: 6px;
        }
        .sig-block p {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
        }
        .sig-block small {
            font-size: 11px;
            color: #666;
        }
        .seal-box {
            width: 90px;
            height: 90px;
            border: 2px dashed #bbb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 10px;
            text-align: center;
            margin: 0 auto;
        }
        .action-bar {
            width: 820px;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .cert-container {
                box-shadow: none;
                border: 2px solid #000;
                width: 100%;
                padding: 25px;
            }
        }
    </style>
</head>
<body>

@php
    $siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();
    $siteName    = $siteSetting->site_name ?? 'Palli Sanchay Bank';
    $siteAddress = $siteSetting->site_address ?? 'Head Office, Dhaka, Bangladesh';
    $siteLogo    = null;
    if (!empty($siteSetting) && !empty($siteSetting->site_logo)) {
        $logoPath = ltrim($siteSetting->site_logo, '/');
        if (file_exists(public_path($logoPath))) {
            $siteLogo = asset($logoPath);
        }
    }
    if (!$siteLogo) {
        $siteLogo = asset('images/logo.png');
    }

    $monthFormatted = $salaryMonth ? date('F, Y', strtotime($salaryMonth)) : date('F, Y');
    $taxDeducted    = $payroll ? (float) $payroll->tax_deduct : (float) ($taxProfile->monthly_tax_deduction ?? 0);
    $grossSalary    = $payroll ? (float) $payroll->gross_salary : (float) ($user->gross_salary ?? 0);
    $basicSalary    = $payroll ? (float) $payroll->b_salary : (float) ($user->basic_salary ?? 0);
    $taxableIncome  = max(0, ($grossSalary * 12) - 350000);

    $taxInWords = 'Zero Taka Only';
    if ($taxDeducted > 0) {
        try {
            $numberToWords = new \NumberToWords\NumberToWords();
            $numberTransformer = $numberToWords->getNumberTransformer('en');
            $taxInWords = ucwords($numberTransformer->toWords((int)$taxDeducted)) . ' Taka Only';
        } catch (\Exception $e) {
            $taxInWords = number_format($taxDeducted, 2) . ' Taka Only';
        }
    }

    $refNo = 'BANK/TAX/' . date('Y') . '/' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
@endphp

<div class="action-bar">
    <a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back</a>
    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fas fa-print me-1"></i> Print Certificate</button>
</div>

<div class="cert-container">
    {{-- Header --}}
    <div class="bank-header">
        <div class="bank-logo-section">
            <img src="{{ $siteLogo }}" alt="Bank Logo" class="bank-logo" onerror="this.style.display='none'">
            <div class="bank-title">
                <h2>{{ $siteName }}</h2>
                <p><i class="fas fa-map-marker-alt me-1"></i> {{ $siteAddress }}</p>
                <p><strong>Human Resources & Financial Services Division</strong></p>
            </div>
        </div>
        <div class="cert-ref-block">
            <p><strong>Ref No:</strong> {{ $refNo }}</p>
            <p><strong>Issue Date:</strong> {{ date('d-M-Y') }}</p>
        </div>
    </div>

    {{-- Title Box --}}
    <div class="cert-title-box">
        <h3>INCOME TAX DEDUCTION CERTIFICATE</h3>
        <p>Issued Under Section 50 of the Income Tax Act, 2023</p>
        <p><strong>Period / Month:</strong> {{ $monthFormatted }} | <strong>Assessment Year:</strong> {{ $activeYear->year_name ?? (date('Y') . '-' . (date('Y')+1)) }}</p>
    </div>

    {{-- Employee & Tax Info Grid --}}
    <table class="info-grid">
        <tr>
            <td class="label-cell">Employee Name</td>
            <td class="value-cell"><strong>{{ $user->name }} {{ $user->last_name }}</strong></td>
            <td class="label-cell">Employee ID</td>
            <td class="value-cell"><strong>{{ $user->emp_id ?: ('EMP-' . $user->id) }}</strong></td>
        </tr>
        <tr>
            <td class="label-cell">Designation</td>
            <td class="value-cell">{{ optional($user->designation)->desi_name ?? 'N/A' }}</td>
            <td class="label-cell">Department</td>
            <td class="value-cell">{{ optional($user->department)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Branch Office</td>
            <td class="value-cell">{{ optional($user->branch)->branch_name ?? 'Head Office' }}</td>
            <td class="label-cell">E-TIN Number</td>
            <td class="value-cell"><strong>{{ $taxProfile->tin_number ?? 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td class="label-cell">Tax Circle / Zone</td>
            <td class="value-cell">{{ ($taxProfile->tax_circle ?? 'N/A') . ' / ' . ($taxProfile->tax_zone ?? 'N/A') }}</td>
            <td class="label-cell">Filing Status</td>
            <td class="value-cell"><span class="badge bg-success">{{ $taxProfile->filing_status ?? 'Registered' }}</span></td>
        </tr>
        <tr>
            <td class="label-cell">Bank Account No</td>
            <td class="value-cell">{{ $user->account_no ?? 'N/A' }}</td>
            <td class="label-cell">Salary Scale / Grade</td>
            <td class="value-cell">Grade {{ optional($user->salaryGrade)->grade ?? 'N/A' }}</td>
        </tr>
    </table>

    {{-- Salary & Tax Breakdown Table --}}
    <h6 style="font-weight: 700; color: #0177bc; margin-bottom: 8px;">1. Earnings & Tax Deduction Breakdown</h6>
    <table class="table-custom">
        <thead>
            <tr>
                <th>Description of Particulars</th>
                <th style="text-align: right;">Monthly Amount (BDT)</th>
                <th style="text-align: right;">Annualized Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td style="text-align: right;">৳ {{ number_format($basicSalary, 2) }}</td>
                <td style="text-align: right;">৳ {{ number_format($basicSalary * 12, 2) }}</td>
            </tr>
            <tr>
                <td>Gross Salary & Allowances</td>
                <td style="text-align: right;">৳ {{ number_format($grossSalary, 2) }}</td>
                <td style="text-align: right;">৳ {{ number_format($grossSalary * 12, 2) }}</td>
            </tr>
            <tr>
                <td>Less: Tax-Exempt Allowances & Exemption Threshold</td>
                <td style="text-align: right; color: #28a745;">-</td>
                <td style="text-align: right; color: #28a745;">(৳ 350,000.00)</td>
            </tr>
            <tr style="font-weight: 600; background-color: #f0f7fc;">
                <td>Estimated Taxable Annual Income</td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;">৳ {{ number_format($taxableIncome, 2) }}</td>
            </tr>
            <tr>
                <td>Approved Investment Rebate (15% Claimed)</td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right; color: #28a745;">(৳ {{ number_format($taxProfile->rebate_claimed ?? 0, 2) }})</td>
            </tr>
            <tr style="font-weight: 700; background-color: #fff3cd;">
                <td><strong>Income Tax Deducted at Source (TDS) for {{ $monthFormatted }}</strong></td>
                <td style="text-align: right; color: #dc3545; font-size: 13px;" colspan="2"><strong>৳ {{ number_format($taxDeducted, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Official Declaration Clause --}}
    <div class="declaration-box">
        <strong>CERTIFICATION STATEMENT:</strong><br>
        This is to certify that <strong>{{ $user->name }} {{ $user->last_name }}</strong>, holding Designation: <strong>{{ optional($user->designation)->desi_name ?? 'N/A' }}</strong> (Employee ID: <strong>{{ $user->emp_id ?: ('EMP-' . $user->id) }}</strong>, E-TIN: <strong>{{ $taxProfile->tin_number ?? 'N/A' }}</strong>), was paid salary and allowances as detailed above.
        Income tax amounting to <strong>BDT {{ number_format($taxDeducted, 2) }} ({{ $taxInWords }})</strong> has been deducted at source under Section 50 of the Income Tax Act, 2023 for the month of <strong>{{ $monthFormatted }}</strong> and duly credited into the Government Treasury Account.
    </div>

    {{-- Signatures --}}
    <div class="signature-section">
        <div class="sig-block">
            <div class="sig-line"></div>
            <p>Prepared By</p>
            <small>Accounts & Payroll Officer</small>
        </div>

        <div class="seal-box">
            OFFICIAL<br>BANK<br>SEAL
        </div>

        <div class="sig-block">
            <div class="sig-line"></div>
            <p>Authorized Signature</p>
            <small>Head of HR & Financial Operations</small>
        </div>
    </div>
</div>

</body>
</html>
