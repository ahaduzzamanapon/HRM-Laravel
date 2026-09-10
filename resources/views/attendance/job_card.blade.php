@php
    if (empty($job_card) || count($job_card) === 0) {
        echo "<p style='text-align:center;padding:40px;color:#888;font-family:sans-serif;'>No job card records found for selected criteria.</p>";
        return;
    }
    $siteSetting = \App\Models\SiteSetting::first();
    $siteName    = $siteSetting->site_name    ?? 'Mysoft Heaven (BD) Ltd';
    $siteAddress = $siteSetting->site_address ?? 'P.R.Tower, 924/1, Level 8, Begum Rokeya Sarani, Shewrapara , Mirpur, Dhaka-1216.';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Job Card Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; background: #eef2f5; color: #333; font-size: 13px; padding: 20px; }

    .card-container { max-width: 960px; margin: 0 auto; }

    .job-card-wrapper {
        background: #ffffff;
        border: 1px solid #d0d7de;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 30px;
        padding: 24px 28px;
        page-break-inside: avoid;
    }

    /* Header Section */
    .header-section {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }
    .company-title {
        font-size: 22px;
        font-weight: bold;
        color: #111111;
        margin-bottom: 4px;
    }
    .company-address {
        font-size: 12px;
        color: #555555;
        margin-bottom: 8px;
    }
    .report-heading {
        font-size: 14px;
        font-weight: bold;
        color: #222222;
    }

    /* Employee Short Info Table */
    .emp-info-table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }
    .emp-info-table td {
        padding: 4px 8px;
        font-size: 13px;
        vertical-align: middle;
    }
    .emp-info-table .info-label {
        font-weight: bold;
        color: #222222;
        text-align: right;
        width: 12%;
    }
    .emp-info-table .info-val {
        color: #444444;
        width: 38%;
    }

    /* Job Card Attendance Table */
    .att-data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }
    .att-data-table th {
        background-color: #f2f4f7;
        color: #111111;
        font-size: 13px;
        font-weight: bold;
        padding: 9px 12px;
        border: 1px solid #d0d7de;
        text-align: center;
    }
    .att-data-table td {
        padding: 8px 12px;
        font-size: 13px;
        border: 1px solid #e1e4e8;
        color: #333333;
    }
    .att-data-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .att-data-table tbody tr:hover {
        background-color: #f0f4f9;
    }

    /* Floating Print Button */
    .print-float-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: #0177bc;
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(1, 119, 188, 0.3);
        transition: all 0.2s ease;
    }
    .print-float-btn:hover {
        background: #005a90;
        transform: translateY(-2px);
    }

    @media print {
        body { background: #ffffff; padding: 0; }
        .card-container { max-width: 100%; }
        .job-card-wrapper {
            border: none;
            box-shadow: none;
            margin-bottom: 0;
            padding: 10px;
            page-break-after: always;
        }
        .job-card-wrapper:last-child { page-break-after: avoid; }
        .print-float-btn { display: none; }
    }
</style>
</head>
<body>

<button class="print-float-btn" onclick="window.print()">🖨 Print Job Card</button>

<div class="card-container">
@foreach ($job_card as $employeeId => $records)
@php
    $employee    = optional($records->first()->user);
    $deptName    = optional($employee->department)->name        ?? 'N/A';
    $desgName    = optional($employee->designation)->desi_name   ?? 'N/A';
    $doj         = $employee->date_of_join  ? date('d-M-Y', strtotime($employee->date_of_join))  : 'N/A';
    $dob         = $employee->date_of_birth ? date('d-M-Y', strtotime($employee->date_of_birth)) : 'N/A';
    $fromDateFmt = date('Y-m-d', strtotime($fromDate));
    $toDateFmt   = $toDate ? date('Y-m-d', strtotime($toDate)) : $fromDateFmt;
@endphp

<div class="job-card-wrapper">
    {{-- Company Header --}}
    <div class="header-section">
        <h1 class="company-title">{{ $siteName }}</h1>
        <p class="company-address">{{ $siteAddress }}</p>
        <h2 class="report-heading">Job Card Report from {{ $fromDateFmt }} -TO- {{ $toDateFmt }}</h2>
    </div>

    {{-- Employee Short Info --}}
    <table class="emp-info-table">
        <tr>
            <td class="info-label">Emp ID:</td>
            <td class="info-val">{{ $employee->emp_id ?? 'N/A' }}</td>
            <td class="info-label">Name:</td>
            <td class="info-val">{{ $employee->name }} {{ $employee->last_name }}</td>
        </tr>
        <tr>
            <td class="info-label">Dept:</td>
            <td class="info-val">{{ $deptName }}</td>
            <td class="info-label">Desig:</td>
            <td class="info-val">{{ $desgName }}</td>
        </tr>
        <tr>
            <td class="info-label">DOJ:</td>
            <td class="info-val">{{ $doj }}</td>
            <td class="info-label">DOB:</td>
            <td class="info-val">{{ $dob }}</td>
        </tr>
    </table>

    {{-- Attendance Data Table --}}
    <table class="att-data-table">
        <thead>
            <tr>
                <th style="width: 18%;">Date</th>
                <th style="width: 18%;">In Time</th>
                <th style="width: 18%;">Out Time</th>
                <th style="width: 22%;">Attendance Status</th>
                <th style="width: 24%;">Comment</th>
            </tr>
        </thead>
        <tbody>
        @foreach($records as $data)
        @php
            $st = $data->status ?? 'Absent';
            $isSkip = in_array($st, ['Off Day', 'Leave', 'Absent', 'Holiday']);
            $inTime  = $isSkip ? '' : ($data->clock_in  ? date('h:i:s a', strtotime($data->clock_in))  : '');
            $outTime = $isSkip ? '' : ($data->clock_out ? date('h:i:s a', strtotime($data->clock_out)) : '');

            $displayStatus = match($st) {
                'Present' => 'P',
                'Absent'  => 'A',
                'Off Day' => 'DAY OFF',
                'Holiday' => 'HOLIDAY',
                'Leave', 'HLeave' => 'LEAVE',
                default   => strtoupper($st),
            };

            $comment = 'N/A';
            if ($data->late_status == 1 && !empty($data->late_time)) {
                $comment = 'Late (' . $data->late_time . 'm)';
            } elseif ($data->late_status == 1) {
                $comment = 'Late Entry';
            }
        @endphp
            <tr>
                <td style="text-align: center;">{{ date('Y-m-d', strtotime($data->attendance_date)) }}</td>
                <td style="text-align: center;">{{ $inTime }}</td>
                <td style="text-align: center;">{{ $outTime }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $displayStatus }}</td>
                <td style="text-align: center;">{{ $comment }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @php
        $totalPresent = 0;
        $totalAbsent  = 0;
        $totalLate    = 0;
        $totalLeave   = 0;
        $totalOffDay  = 0;
        $totalHoliday = 0;
        $totalDays    = count($records);

        foreach ($records as $d) {
            $statusStr = $d->status ?? 'Absent';
            if (in_array($statusStr, ['Present', 'HalfDay', 'Continue'])) {
                $totalPresent++;
            } elseif ($statusStr === 'Absent') {
                $totalAbsent++;
            } elseif (in_array($statusStr, ['Leave', 'HLeave'])) {
                $totalLeave++;
            } elseif ($statusStr === 'Off Day') {
                $totalOffDay++;
            } elseif ($statusStr === 'Holiday') {
                $totalHoliday++;
            }

            if ($d->late_status == 1) {
                $totalLate++;
            }
        }
    @endphp

    {{-- Attendance Summary Table --}}
    <table class="att-summary-table" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f4f7;">
                <th style="border: 1px solid #d0d7de; padding: 8px 12px; font-weight: bold; text-align: center; color: #111;">Total Present (P)</th>
                <th style="border: 1px solid #d0d7de; padding: 8px 12px; font-weight: bold; text-align: center; color: #111;">Total Absent (A)</th>
                <th style="border: 1px solid #d0d7de; padding: 8px 12px; font-weight: bold; text-align: center; color: #111;">Total Late (L)</th>
                <th style="border: 1px solid #d0d7de; padding: 8px 12px; font-weight: bold; text-align: center; color: #111;">Total Leave (LV)</th>
                <th style="border: 1px solid #d0d7de; padding: 8px 12px; font-weight: bold; text-align: center; color: #111;">Day Off / Holiday</th>
                <th style="border: 1px solid #d0d7de; padding: 8px 12px; font-weight: bold; text-align: center; color: #111;">Total Days</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #e1e4e8; padding: 10px; text-align: center; font-weight: bold; color: #28a745; font-size: 15px;">{{ $totalPresent }}</td>
                <td style="border: 1px solid #e1e4e8; padding: 10px; text-align: center; font-weight: bold; color: #dc3545; font-size: 15px;">{{ $totalAbsent }}</td>
                <td style="border: 1px solid #e1e4e8; padding: 10px; text-align: center; font-weight: bold; color: #d97706; font-size: 15px;">{{ $totalLate }}</td>
                <td style="border: 1px solid #e1e4e8; padding: 10px; text-align: center; font-weight: bold; color: #0284c7; font-size: 15px;">{{ $totalLeave }}</td>
                <td style="border: 1px solid #e1e4e8; padding: 10px; text-align: center; font-weight: bold; color: #6b7280; font-size: 15px;">{{ $totalOffDay + $totalHoliday }}</td>
                <td style="border: 1px solid #e1e4e8; padding: 10px; text-align: center; font-weight: bold; color: #111827; font-size: 15px;">{{ $totalDays }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endforeach
</div>

</body>
</html>
