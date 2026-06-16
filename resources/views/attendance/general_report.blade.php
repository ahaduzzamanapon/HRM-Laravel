@php
    if (empty($general_reports) || count($general_reports) === 0) {
        echo "<p>No data found.</p>";
        return;
    }
    $siteSetting = \App\Models\SiteSetting::first();
    $siteName    = $siteSetting->site_name    ?? 'Palli Sanchay Bank';
    $siteLogo    = $siteSetting->site_logo    ? asset('images/site/' . $siteSetting->site_logo) : asset('logo.png');
    $siteAddress = $siteSetting->site_address ?? 'Head Office, Dhaka';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employees General Report</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', Arial, sans-serif; }
    body { background: #f4f7fb; color: #2c3e50; }

    .report-wrapper { max-width: 1100px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.1); overflow: hidden; }

    /* Header */
    .report-header { background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%); color: white; padding: 20px 30px; display: flex; align-items: center; gap: 18px; }
    .report-header img { width: 60px; height: 60px; object-fit: contain; background: #fff; border-radius: 8px; padding: 4px; }
    .report-header h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 2px; }
    .report-header p  { font-size: 0.85rem; opacity: 0.85; }

    .report-title { background: #eef3fa; text-align: center; padding: 10px; border-bottom: 1px solid #dde6f0; font-size: 0.9rem; font-weight: 700; color: #1e3a5f; letter-spacing: 0.5px; }

    /* Table */
    table { width: 100%; border-collapse: collapse; }
    thead th { background: #1e3a5f; color: white; padding: 10px 10px; font-size: 0.75rem; font-weight: 600; text-align: center; white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #f0f0f0; }
    tbody tr:nth-child(even) { background: #f8faff; }
    tbody tr:hover { background: #e3f0fc; }
    tbody td { padding: 9px 10px; font-size: 0.78rem; text-align: center; vertical-align: middle; }

    .emp-name { font-weight: 600; color: #1e3a5f; text-align: left; }
    .sl-cell { color: #8e9aab; font-weight: 700; width: 40px; }

    .emp-photo { width: 38px; height: 38px; object-fit: cover; border-radius: 50%; border: 2px solid #dde6f0; }
    .emp-photo-placeholder { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #1e3a5f, #2d6a9f); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; }

    .print-btn { position: fixed; bottom: 24px; right: 24px; background: #1e3a5f; color: white; border: none; border-radius: 50px; padding: 12px 24px; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 16px rgba(30,58,95,0.4); }
    .print-btn:hover { background: #2d6a9f; }
    @media print { .print-btn { display: none; } body { background: white; } .report-wrapper { box-shadow: none; border-radius: 0; } }
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨 Print</button>

<div class="report-wrapper">
    {{-- Header --}}
    <div class="report-header">
        <img src="{{ $siteLogo }}" alt="{{ $siteName }}">
        <div>
            <h2>{{ $siteName }}</h2>
            <p>{{ $siteAddress }}</p>
        </div>
    </div>
    <div class="report-title">Employees General Report</div>

    <table>
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Photo</th>
                <th style="text-align:left;">Employee Name</th>
                <th>Emp. ID</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Joining Date</th>
                <th>Gross Salary</th>
                <th>Bank Name</th>
                <th>Bank Branch</th>
                <th>Account No.</th>
                <th>Mobile</th>
            </tr>
        </thead>
        <tbody>
        @php $sl = 0; @endphp
        @foreach ($general_reports as $data)
        @php
            $sl++;
            $department  = $data->department;
            $designation = $data->designation;
            $bank        = $data->bank_id ? \App\Models\BankSetup::find($data->bank_id) : null;
            $initials    = strtoupper(substr($data->name ?? '', 0, 1)) . strtoupper(substr($data->last_name ?? '', 0, 1));
            $joinDate    = $data->date_of_join && $data->date_of_join !== '0000-00-00'
                           ? date('d-m-Y', strtotime($data->date_of_join))
                           : '—';
        @endphp
        <tr>
            <td class="sl-cell">{{ $sl }}</td>
            <td>
                @if($data->image && file_exists(public_path($data->image)))
                    <img class="emp-photo" src="{{ asset($data->image) }}" alt="">
                @else
                    <span class="emp-photo-placeholder">{{ $initials ?: '?' }}</span>
                @endif
            </td>
            <td class="emp-name">{{ $data->name }} {{ $data->last_name }}</td>
            <td>{{ $data->emp_id ?? '—' }}</td>
            <td>{{ optional($department)->name ?? '—' }}</td>
            <td>{{ optional($designation)->desi_name ?? '—' }}</td>
            <td>{{ $joinDate }}</td>
            <td>{{ number_format($data->gross_salary ?? 0, 2) }}</td>
            <td>{{ optional($bank)->bank_name ?? '—' }}</td>
            <td>{{ optional($bank)->branch_name ?? '—' }}</td>
            <td>{{ $data->account_no ?? '—' }}</td>
            <td>{{ $data->phone_number ?? '—' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
