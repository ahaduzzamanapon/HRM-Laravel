@php
    if (empty($attendanceDatas) || count($attendanceDatas) === 0) {
        echo "<p style='text-align:center;padding:40px;color:#888;'>No data found.</p>";
        return;
    }
    $showTime    = in_array($filterType, ['present', 'all', 'late', 'intime_only', 'outtime_only']);
    $inTimeOnly  = $filterType === 'intime_only';
    $outTimeOnly = $filterType === 'outtime_only';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance Report — {{ date('d M Y', strtotime($date)) }}</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', Arial, sans-serif; }
    body { background: #f4f7fb; color: #2c3e50; }

    .report-wrapper { max-width: 900px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.1); overflow: hidden; }

    .report-header { background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%); color: white; padding: 24px 30px; }
    .report-header h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; }
    .report-header .meta { font-size: 0.85rem; opacity: 0.85; display: flex; gap: 20px; flex-wrap: wrap; }
    .report-header .meta span { display: flex; align-items: center; gap: 6px; }

    .legend { padding: 14px 30px; background: #f8faff; border-bottom: 1px solid #e8eef5; display: flex; gap: 16px; flex-wrap: wrap; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600; }
    .badge-dot { width: 10px; height: 10px; border-radius: 50%; }

    table { width: 100%; border-collapse: collapse; }
    thead th { background: #1e3a5f; color: white; padding: 11px 14px; font-size: 0.8rem; font-weight: 600; text-align: left; white-space: nowrap; }
    thead th:first-child { width: 50px; text-align: center; }
    tbody tr { border-bottom: 1px solid #f0f0f0; transition: background 0.1s; }
    tbody tr:hover { background: #f8faff; }
    tbody td { padding: 10px 14px; font-size: 0.82rem; }
    tbody td:first-child { text-align: center; color: #8e9aab; font-size: 0.78rem; font-weight: 600; }

    .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.73rem; font-weight: 700; letter-spacing: 0.3px; }
    .badge-present  { background: #e8f5e9; color: #2e7d32; }
    .badge-absent   { background: #fdecea; color: #c62828; }
    .badge-leave    { background: #f3e5f5; color: #6a1b9a; }
    .badge-holiday  { background: #fff8e1; color: #f57f17; }
    .badge-offday   { background: #e8eaf6; color: #3949ab; }
    .badge-continue { background: #e0f7fa; color: #00695c; }
    .badge-halfday  { background: #fff3e0; color: #e65100; }

    .late-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ff6b35; margin-left: 5px; }
    .time-cell { font-size: 0.78rem; color: #555; white-space: nowrap; }
    .emp-name { font-weight: 600; color: #1e3a5f; }
    .emp-id-cell { font-size: 0.75rem; color: #8e9aab; }

    .summary-bar { display: flex; gap: 0; border-top: 1px solid #e8eef5; }
    .summary-item { flex: 1; text-align: center; padding: 14px; border-right: 1px solid #e8eef5; }
    .summary-item:last-child { border-right: none; }
    .summary-num { font-size: 1.4rem; font-weight: 700; }
    .summary-label { font-size: 0.72rem; color: #8e9aab; font-weight: 600; margin-top: 2px; }

    .print-btn { position: fixed; bottom: 24px; right: 24px; background: #1e3a5f; color: white; border: none; border-radius: 50px; padding: 12px 24px; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 16px rgba(30,58,95,0.4); transition: all 0.2s; }
    .print-btn:hover { background: #2d6a9f; transform: translateY(-2px); }
    @media print { .print-btn { display: none; } body { background: white; } .report-wrapper { box-shadow: none; border-radius: 0; } }
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨 Print</button>

<div class="report-wrapper">
    <div class="report-header">
        <h2>Daily Attendance Report — {{ ucfirst($filterType) }}</h2>
        <div class="meta">
            <span>📅 {{ date('l, d F Y', strtotime($date)) }}</span>
            <span>👥 {{ count($attendanceDatas) }} Records</span>
            <span>⏱ Generated: {{ date('h:i A') }}</span>
        </div>
    </div>

    @php
        $presentCnt  = collect($attendanceDatas)->where('status','Present')->count();
        $absentCnt   = collect($attendanceDatas)->where('status','Absent')->count();
        $leaveCnt    = collect($attendanceDatas)->whereIn('status',['Leave','HLeave'])->count();
        $lateCnt     = collect($attendanceDatas)->where('late_status',1)->count();
    @endphp

    <div class="summary-bar">
        <div class="summary-item">
            <div class="summary-num" style="color:#1e3a5f">{{ count($attendanceDatas) }}</div>
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
            <div class="summary-label">ON LEAVE</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Emp. ID</th>
                <th>Name</th>
                @if($showTime)
                    <th>{{ $outTimeOnly ? '—' : 'In Time' }}</th>
                    @if(!$inTimeOnly)
                        <th>Out Time</th>
                    @endif
                @endif
                <th>Status</th>
                @if($filterType === 'late' || $filterType === 'all')
                    <th>Late (min)</th>
                @endif
            </tr>
        </thead>
        <tbody>
        @foreach($attendanceDatas as $index => $data)
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
                @if($showTime)
                    <td class="time-cell">
                        @if(!$outTimeOnly)
                            {{ $data->clock_in ? date('h:i A', strtotime($data->clock_in)) : '—' }}
                            @if($data->late_status) <span class="late-dot" title="Late"></span> @endif
                        @else
                            —
                        @endif
                    </td>
                    @if(!$inTimeOnly)
                        <td class="time-cell">{{ $data->clock_out ? date('h:i A', strtotime($data->clock_out)) : '—' }}</td>
                    @endif
                @endif
                <td><span class="status-badge {{ $badgeClass }}">{{ $status }}</span></td>
                @if($filterType === 'late' || $filterType === 'all')
                    <td style="text-align:center; font-size:0.78rem;">
                        {{ $data->late_status ? $data->late_time . ' min' : '—' }}
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
