<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pension Payslip - {{ $disbursement->profile->user->name ?? 'Employee' }}</title>
    <style>
        @page {
            size: a5 portrait;
            margin: 15px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #0056b3;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0056b3;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .info-table, .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            color: #555;
            width: 25%;
        }
        .info-table td.value {
            color: #333;
            width: 25%;
        }
        .financial-table th, .financial-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .financial-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .financial-table td.amount {
            text-align: right;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .financial-table tr.total-row td {
            background-color: #f1f3f5;
            font-weight: bold;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            height: 50px;
            font-size: 10px;
            color: #555;
        }
        .signature-line {
            border-top: 1px solid #666;
            width: 80%;
            margin: 0 auto 3px auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Pension Disbursement Payslip</h1>
        <p>HRM Pension Management System</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Employee Name:</td>
            <td class="value">{{ $disbursement->profile->user->name ?? 'N/A' }} {{ $disbursement->profile->user->last_name ?? '' }}</td>
            <td class="label">Disbursement Month:</td>
            <td class="value">{{ $disbursement->disbursement_month }}</td>
        </tr>
        <tr>
            <td class="label">Employee ID:</td>
            <td class="value">{{ $disbursement->profile->user->emp_id ?? 'N/A' }}</td>
            <td class="label">Payment Method:</td>
            <td class="value">{{ $disbursement->payment_method }}</td>
        </tr>
        <tr>
            <td class="label">Retirement Date:</td>
            <td class="value">{{ $disbursement->profile->retirement_date ?? 'N/A' }}</td>
            <td class="label">Bank Reference:</td>
            <td class="value">{{ $disbursement->bank_reference ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Pension Scheme:</td>
            <td class="value">{{ $disbursement->profile->scheme->name ?? 'N/A' }}</td>
            <td class="label">Payment Date:</td>
            <td class="value">{{ $disbursement->paid_at ? $disbursement->paid_at->format('d M Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Payment Status:</td>
            <td class="value" colspan="3">
                <span class="status-badge status-{{ strtolower($disbursement->status) }}">
                    {{ $disbursement->status }}
                </span>
            </td>
        </tr>
    </table>

    <div class="section-title">Financial Breakdown</div>

    <table class="financial-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right; width: 180px;">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $calc = $disbursement->profile->calculation;
                $grossPension = $calc->gross_pension ?? 0;
                $medicalAllowance = $calc->medical_allowance ?? 1500;
                $commutedAmount = $calc->commuted_amount ?? 0;
                $monthlyPension = $calc->monthly_pension ?? ($grossPension * 0.5);
                $netPayable = $disbursement->net_payable;
                $totalPension = $commutedAmount + $netPayable;
            @endphp
            <tr>
                <td>Gross Monthly Pension</td>
                <td class="amount">{{ number_format($grossPension, 2) }}</td>
            </tr>
            <tr>
                <td>Commuted Amount (50% Lump Sum Gratuity)</td>
                <td class="amount">{{ number_format($commutedAmount, 2) }}</td>
            </tr>
            <tr>
                <td>Monthly Pension (Remaining 50% after Commutation)</td>
                <td class="amount">{{ number_format($monthlyPension, 2) }}</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td class="amount">{{ number_format($medicalAllowance, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Net Monthly Payable</td>
                <td class="amount">{{ number_format($netPayable, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Package (One-time Lump Sum + Net Monthly)</td>
                <td class="amount" style="color: #0056b3;">{{ number_format($totalPension, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    Prepared By (HR Admin)
                </td>
                <td>
                    <div class="signature-line"></div>
                    Approved By (Accounts)
                </td>
                <td>
                    <div class="signature-line"></div>
                    Recipient Signature
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
