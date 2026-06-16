@php
    if (empty($job_card) || count($job_card) === 0) {
        echo "<p style='text-align:center;padding:40px;color:#888;'>No data found.</p>";
        return;
    }
    $siteSetting = \App\Models\SiteSetting::first();
    $siteName    = $siteSetting->site_name    ?? 'Palli Sanchay Bank';
    $siteAddress = $siteSetting->site_address ?? 'Head Office, Dhaka';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Job Card Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; background: #f0f4f8; color: #222; font-size: 12px; }

    .card-wrapper { max-width: 820px; margin: 20px auto; }

    /* Each employee card */
    .emp-card {
        background: #fff;
        border: 1px solid #ccc;
        margin-bottom: 30px;
        page-break-inside: avoid;
    }

    /* Company Header */
    .company-header {
        text-align: center;
        border-bottom: 2px solid #1e3a5f;
        padding: 12px 16px 10px;
    }
    .company-name { font-size: 16px; font-weight: 700; color: #1e3a5f; }
    .company-address { font-size: 11px; color: #555; margin-top: 2px; }
    .report-title {
        font-size: 11px; font-weight: 700; color: #c62828;
        margin-top: 6px; text-decoration: underline; letter-spacing: 0.3px;
    }

    /* Employee info section */
    .emp-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        padding: 10px 16px;
        border-bottom: 1px solid #ddd;
        background: #f8faff;
    }
    .emp-info-row { display: flex; gap: 6px; padding: 3px 0; }
    .info-label { font-weight: 700; color: #333; min-width: 110px; }
    .info-value { color: #1e3a5f; font-weight: 600; }

    /* Attendance table */
    .att-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .att-table th {
        background: #1e3a5f;
        color: #fff;
        padding: 7px 10px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #1e3a5f;
    }
    .att-table td {
        padding: 6px 10px;
        text-align: center;
        border: 1px solid #ddd;
        font-size: 11px;
    }
    .att-table tbody tr:nth-child(even) { background: #f5f8ff; }
    .att-table tbody tr:hover { background: #e3edf8; }
    tr.row-absent  { background: #fdecea !important; color: #c62828; }
    tr.row-late    { background: #fff8e1 !important; color: #e65100; }
    tr.row-leave   { background: #f3e5f5 !important; color: #6a1b9a; }
    tr.row-offday  { background: #e8eaf6 !important; color: #3949ab; }

    /* Summary row */
    .summary-section { border-top: 1.5px solid #1e3a5f; }
    .summary-table { width: 100%; border-collapse: collapse; }
    .summary-table th {
        background: #eef3fa;
        color: #1e3a5f;
        padding: 7px 10px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #ccc;
    }
    .summary-table td {
        padding: 7px 10px;
        text-align: center;
        border: 1px solid #ccc;
        font-size: 12px;
        font-weight: 700;
        color: #1e3a5f;
    }

    /* Print button */
    .print-btn {
        position: fixed; bottom: 24px; right: 24px;
        background: #1e3a5f; color: white; border: none;
        border-radius: 50px; padding: 11px 22px;
        font-size: 13px; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 14px rgba(30,58,95,0.4);
    }
    @media print {
        .print-btn { display: none; }
        body { background: white; }
        .card-wrapper { margin: 0; }
        .emp-card { margin-bottom: 0; page-break-after: always; border: 1px solid #999; }
        .emp-card:last-child { page-break-after: avoid; }
    }
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨 Print</button>

<div class="card-wrapper">

@foreach ($job_card as $employeeId => $records)
@php
    $employee    = optional($records->first()->user);
    $deptName    = optional($employee->department)->name     ?? '—';
    $desgName    = optional($employee->designation)->desi_name ?? '—';
    $totalDays   = count($records);
    $totalPresent = 0; $totalAbsent = 0; $totalLeave = 0; $totalOff = 0; $totalLate = 0;
@endphp

<div class="emp-card">

    {{-- Company Header --}}
    <div class="company-header">
        <div class="company-name">{{ $siteName }}</div>
        <div class="company-address">{{ $siteAddress }}</div>
        <div class="report-title">
            Job Card Report &nbsp;|&nbsp;
            {{ date('d M Y', strtotime($fromDate)) }}
            @if($toDate && $toDate !== $fromDate)
                &nbsp;to&nbsp; {{ date('d M Y', strtotime($toDate)) }}
            @endif
        </div>
    </div>

    {{-- Employee Info --}}
    <div class="emp-info">
        <div>
            <div class="emp-info-row">
                <span class="info-label">Employee Name :</span>
                <span class="info-value">{{ $employee->name }} {{ $employee->last_name }}</span>
            </div>
            <div class="emp-info-row">
                <span class="info-label">Department :</span>
                <span class="info-value">{{ $deptName }}</span>
            </div>
        </div>
        <div>
            <div class="emp-info-row">
                <span class="info-label">Emp. ID :</span>
                <span class="info-value">{{ $employee->emp_id ?? '—' }}</span>
            </div>
            <div class="emp-info-row">
                <span class="info-label">Designation :</span>
                <span class="info-value">{{ $desgName }}</span>
            </div>
        </div>
    </div>

    {{-- Attendance Table --}}
    <table class="att-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Day</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Late (min)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($records as $idx => $data)
        @php
            $st = $data->status ?? 'Absent';
            $isSkip = in_array($st, ['Off Day', 'Leave', 'Absent', 'Holiday']);
            $inTime  = $isSkip ? '—' : ($data->clock_in  ? date('h:i A', strtotime($data->clock_in))  : '—');
            $outTime = $isSkip ? '—' : ($data->clock_out ? date('h:i A', strtotime($data->clock_out)) : '—');

            if ($st === 'Present') $totalPresent++;
            elseif ($st === 'Absent') $totalAbsent++;
            elseif (in_array($st, ['Leave','HLeave'])) $totalLeave++;
            elseif ($st === 'Off Day') $totalOff++;
            if ($data->late_status == 1) $totalLate++;

            $rowClass = match(true) {
                $st === 'Absent'               => 'row-absent',
                $st === 'Off Day'              => 'row-offday',
                in_array($st,['Leave','HLeave'])=> 'row-leave',
                ($data->late_status == 1 && $st === 'Present') => 'row-late',
                default => '',
            };
        @endphp
        <tr class="{{ $rowClass }}">
            <td>{{ $idx + 1 }}</td>
            <td>{{ date('d-M-Y', strtotime($data->attendance_date)) }}</td>
            <td>{{ date('D', strtotime($data->attendance_date)) }}</td>
            <td>{{ $inTime }}</td>
            <td>{{ $outTime }}</td>
            <td>{{ $data->late_status && $data->late_time ? $data->late_time . ' min' : '—' }}</td>
            <td>
                @if($st === 'Present') <strong style="color:#2e7d32;">P</strong>
                @elseif($st === 'Absent') <strong style="color:#c62828;">A</strong>
                @elseif(in_array($st,['Leave','HLeave'])) <strong style="color:#6a1b9a;">L</strong>
                @elseif($st === 'Off Day') <strong style="color:#3949ab;">Off</strong>
                @elseif($st === 'Holiday') <strong style="color:#f57f17;">H</strong>
                @else <strong>—</strong>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Summary --}}
    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <th>Total Days</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Leave</th>
                <th>Off Days</th>
                <th>Late</th>
            </tr>
            <tr>
                <td>{{ $totalDays }}</td>
                <td style="color:#2e7d32;">{{ $totalPresent }}</td>
                <td style="color:#c62828;">{{ $totalAbsent }}</td>
                <td style="color:#6a1b9a;">{{ $totalLeave }}</td>
                <td style="color:#3949ab;">{{ $totalOff }}</td>
                <td style="color:#e65100;">{{ $totalLate }}</td>
            </tr>
        </table>
    </div>

</div>{{-- .emp-card --}}
@endforeach

</div>
</body>
</html>
