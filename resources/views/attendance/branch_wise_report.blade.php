@php
    if (empty($branchData) || count($branchData) === 0) {
        echo "<p style='text-align:center;padding:40px;color:#888;'>No data found.</p>";
        return;
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Branch Wise Attendance Report — {{ date('d M Y', strtotime($fromDate)) }}{{ $toDate && $toDate !== $fromDate ? ' to '.date('d M Y', strtotime($toDate)) : '' }}</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', Arial, sans-serif; }
    body { background: #f4f7fb; color: #2c3e50; }

    .page-wrapper { max-width: 960px; margin: 30px auto; }

    /* Branch Section */
    .branch-section { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.09); overflow: hidden; margin-bottom: 32px; }

    .branch-header { background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%); color: white; padding: 20px 28px; }
    .branch-header .branch-name { font-size: 1.3rem; font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
    .branch-header .branch-meta { font-size: 0.82rem; opacity: 0.85; display: flex; gap: 18px; flex-wrap: wrap; margin-top: 6px; }

    /* Report header (date range) */
    .report-meta-bar { background: #eef3fa; padding: 10px 28px; border-bottom: 1px solid #dde6f0; font-size: 0.82rem; color: #546e7a; display: flex; gap: 20px; }

    /* Summary */
    .summary-bar { display: flex; gap: 0; border-bottom: 1px solid #e8eef5; }
    .summary-item { flex: 1; text-align: center; padding: 12px; border-right: 1px solid #e8eef5; }
    .summary-item:last-child { border-right: none; }
    .summary-num { font-size: 1.3rem; font-weight: 700; }
    .summary-label { font-size: 0.7rem; color: #8e9aab; font-weight: 600; margin-top: 2px; }

    /* Table */
    table { width: 100%; border-collapse: collapse; }
    thead th { background: #1e3a5f; color: white; padding: 10px 14px; font-size: 0.78rem; font-weight: 600; text-align: left; white-space: nowrap; }
    thead th:first-child { width: 44px; text-align: center; }
    tbody tr { border-bottom: 1px solid #f0f0f0; }
    tbody tr:hover { background: #f8faff; }
    tbody td { padding: 9px 14px; font-size: 0.81rem; }
    tbody td:first-child { text-align: center; color: #8e9aab; font-size: 0.76rem; font-weight: 600; }

    .emp-name { font-weight: 600; color: #1e3a5f; }
    .emp-id-cell { font-size: 0.74rem; color: #8e9aab; }
    .time-cell { font-size: 0.78rem; color: #555; white-space: nowrap; }
    .dept-cell { font-size: 0.75rem; color: #78909c; }

    .status-badge { display: inline-block; padding: 3px 9px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px; }
    .badge-present  { background: #e8f5e9; color: #2e7d32; }
    .badge-absent   { background: #fdecea; color: #c62828; }
    .badge-leave    { background: #f3e5f5; color: #6a1b9a; }
    .badge-holiday  { background: #fff8e1; color: #f57f17; }
    .badge-offday   { background: #e8eaf6; color: #3949ab; }
    .badge-continue { background: #e0f7fa; color: #00695c; }
    .badge-halfday  { background: #fff3e0; color: #e65100; }

    .late-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ff6b35; margin-left: 5px; }

    .print-btn { position: fixed; bottom: 24px; right: 24px; background: #1e3a5f; color: white; border: none; border-radius: 50px; padding: 12px 24px; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 16px rgba(30,58,95,0.4); }
    .print-btn:hover { background: #2d6a9f; }

    @media print {
        .print-btn { display: none; }
        body { background: white; }
        .page-wrapper { margin: 0; }
        .branch-section { box-shadow: none; border-radius: 0; page-break-after: always; margin-bottom: 0; }
        .branch-section:last-child { page-break-after: avoid; }
    }
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨 Print</button>

<div class="page-wrapper">

@foreach($branchData as $branchName => $records)
@php
    $presentCnt = collect($records)->where('status','Present')->count();
    $absentCnt  = collect($records)->where('status','Absent')->count();
    $leaveCnt   = collect($records)->whereIn('status',['Leave','HLeave'])->count();
    $lateCnt    = collect($records)->where('late_status',1)->count();
    $offCnt     = collect($records)->where('status','Off Day')->count();
@endphp

<div class="branch-section">
    {{-- Branch Header --}}
    <div class="branch-header">
        <div class="branch-name">
            🏢 {{ $branchName }}
        </div>
        <div class="branch-meta">
            <span>📅 {{ date('d F Y', strtotime($fromDate)) }}{{ $toDate && $toDate !== $fromDate ? ' → '.date('d F Y', strtotime($toDate)) : '' }}</span>
            <span>👥 {{ count($records) }} Records</span>
            <span>⏱ Generated: {{ date('h:i A') }}</span>
        </div>
    </div>

    {{-- Summary Bar --}}
    <div class="summary-bar">
        <div class="summary-item">
            <div class="summary-num" style="color:#1e3a5f">{{ count($records) }}</div>
            <div class="summary-label">TOTAL</div>
        </div>
        <div class="summary-item">
            <div class="summary-num" style="color:#2e7d32">{{ $presentCnt }}</div>
            <div class="summary-label">PRESENT</div>
        </div>
        <div class="summary-item">
            <div class="summary-num" style="color:#c62828">{{ $absentCnt }}</div>
            <div class="summary-label">ABSENT</div>
        </div>
        <div class="summary-item">
            <div class="summary-num" style="color:#e65100">{{ $lateCnt }}</div>
            <div class="summary-label">LATE</div>
        </div>
        <div class="summary-item">
            <div class="summary-num" style="color:#6a1b9a">{{ $leaveCnt }}</div>
            <div class="summary-label">LEAVE</div>
        </div>
        <div class="summary-item">
            <div class="summary-num" style="color:#3949ab">{{ $offCnt }}</div>
            <div class="summary-label">OFF DAY</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Emp. ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Status</th>
                <th>Late (min)</th>
            </tr>
        </thead>
        <tbody>
        @foreach($records as $index => $data)
            @php
                $user   = $data->user ?? null;
                $status = $data->status ?? 'Absent';
                $badgeClass = match($status) {
                    'Present'  => 'badge-present',
                    'Absent'   => 'badge-absent',
                    'Leave','HLeave' => 'badge-leave',
                    'Holiday'  => 'badge-holiday',
                    'Off Day'  => 'badge-offday',
                    'Continue' => 'badge-continue',
                    'HalfDay'  => 'badge-halfday',
                    default    => 'badge-absent',
                };
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="emp-id-cell">{{ optional($user)->emp_id ?? $data->employee_id }}</td>
                <td>
                    <div class="emp-name">{{ optional($user)->name }} {{ optional($user)->last_name }}</div>
                </td>
                <td class="dept-cell">{{ optional(optional($user)->department)->name ?? '—' }}</td>
                <td class="time-cell">
                    {{ $data->clock_in ? date('h:i A', strtotime($data->clock_in)) : '—' }}
                    @if($data->late_status) <span class="late-dot" title="Late"></span> @endif
                </td>
                <td class="time-cell">{{ $data->clock_out ? date('h:i A', strtotime($data->clock_out)) : '—' }}</td>
                <td><span class="status-badge {{ $badgeClass }}">{{ $status }}</span></td>
                <td style="text-align:center; font-size:0.78rem;">
                    {{ $data->late_status ? $data->late_time . ' min' : '—' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endforeach

</div>
</body>
</html>
