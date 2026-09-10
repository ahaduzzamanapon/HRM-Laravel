<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Welfare Fund Report - {{ $year }} {{ $month ?: '' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            font-size: 11px;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #0177bc;
            font-size: 18px;
        }
        .header p {
            margin: 4px 0 0;
            color: #666;
            font-size: 11px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 18px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #cbd5e1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .amount-green {
            color: #16a34a;
            font-weight: bold;
        }
        .amount-blue {
            color: #0284c7;
            font-weight: bold;
        }
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Welfare Fund Reports & Analytics</h1>
        <p>Period: {{ $month ? $month . ' ' . $year : 'Year ' . $year }} | Generated on: {{ date('d M Y, h:i A') }}</p>
    </div>

    <div class="section-title">
        1. Contribution Summary (Total: &#2547; {{ number_format($contributions->sum('amount'), 2) }})
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th>Employee Name</th>
                <th>Employee ID</th>
                <th>Contribution Type</th>
                <th>Period</th>
                <th>Date</th>
                <th class="text-right">Amount (&#2547;)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contributions as $index => $c)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $c->user->name ?? 'N/A' }} {{ $c->user->last_name ?? '' }}</td>
                    <td>{{ $c->user->emp_id ?? 'N/A' }}</td>
                    <td>{{ ucfirst($c->contribution_type ?? 'Employee') }}</td>
                    <td>{{ $c->month }} {{ $c->year }}</td>
                    <td>{{ \Carbon\Carbon::parse($c->contribution_date)->format('d M Y') }}</td>
                    <td class="text-right amount-green">&#2547; {{ number_format($c->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No contribution records found.</td>
                </tr>
            @endforelse
        </tbody>
        @if($contributions->count() > 0)
        <tfoot>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="6" class="text-right">Total Contributions:</td>
                <td class="text-right amount-green">&#2547; {{ number_format($contributions->sum('amount'), 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="section-title">
        2. Disbursement Summary (Total: &#2547; {{ number_format($disbursements->sum('approved_amount'), 2) }})
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th>Employee Name</th>
                <th>Support Category</th>
                <th>Application Date</th>
                <th class="text-right">Approved Amount (&#2547;)</th>
                <th class="text-right">Disbursed Amount (&#2547;)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disbursements as $index => $d)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $d->employee->name ?? 'N/A' }} {{ $d->employee->last_name ?? '' }}</td>
                    <td>{{ $d->support_category }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->support_date)->format('d M Y') }}</td>
                    <td class="text-right">&#2547; {{ number_format($d->approved_amount ?? $d->amount ?? $d->financial_assistance, 2) }}</td>
                    <td class="text-right amount-blue">&#2547; {{ number_format($d->disbursed_amount ?? $d->approved_amount ?? $d->amount ?? $d->financial_assistance, 2) }}</td>
                    <td class="text-center">{{ $d->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No disbursement records found.</td>
                </tr>
            @endforelse
        </tbody>
        @if($disbursements->count() > 0)
        <tfoot>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="5" class="text-right">Total Disbursed:</td>
                <td class="text-right amount-blue">&#2547; {{ number_format($disbursements->sum('approved_amount'), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Report generated automatically by HRM System</p>
    </div>

</body>
</html>
