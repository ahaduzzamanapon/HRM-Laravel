<style>
/* Table Container */
.table-responsive {
    /* margin: 20px auto; */
    max-width: 100%;
    overflow-x: auto;
    font-family: Arial, sans-serif;
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
    background-color: #fff;
}

/* Table Head */
.table thead th {
    background-color: #ffffff; /* Bootstrap primary color */
    color: #000000;
    font-weight: bold;
    text-align: center;
    padding: 4px;
    border: 1px solid #000;
    font-size:10px;
}

/* Table Body */
.table tbody td {
    text-align: center;
    padding: 6px 8px;
    border: 1px solid #000;
    font-size:10px;

}

/* Responsive table */
@media (max-width: 768px) {
    .table thead th, .table tbody td {
        font-size: 10px;
        padding: 4px;
    }
    .table-responsive h3 {
        font-size: 16px;
        text-align: center;
    }
}
.payslip-header {
    text-align: center;
}
</style>
@php
    $siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();
    $siteName    = $siteSetting->site_name ?? 'Palli Sanchay Bank';
    $siteAddress = $siteSetting->site_address ?? 'Head Office, Dhaka';
    $siteLogo    = null;
    if (!empty($siteSetting) && !empty($siteSetting->site_logo)) {
        $logoPath = ltrim($siteSetting->site_logo, '/');
        if (file_exists(public_path($logoPath))) {
            $siteLogo = asset($logoPath);
        }
    }
    if (!$siteLogo) {
        $siteLogo = asset('salary_logo.jpg');
    }
@endphp
<button onclick="exportExcel()">Export to Excel</button>
<div class="table-responsive">
    <div class="payslip-header">
        <div style="display: flex; align-items: center;justify-content: center;">
            <img src="{{ $siteLogo }}" alt="Company Logo" style="max-width: 50px; height: auto;">
            <div style="margin-left: 10px;">
                <h3 style="margin: 0;">{{ $siteName }}</h3>
                <p style="margin: 5px 0 0 0; line-height: 1.2;">{{ $siteAddress }}</p>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; align-items: center;justify-content: center; margin-top: 10px;">
            <h5>Tax Statement of {{ count($salary_reports) }} Employees for {{ \Carbon\Carbon::parse($salary_month)->format('F, Y') }}</h5>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>SL NO</th>
                <th>ID NO</th>
                <th>EMP Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Gross Salary</th>
                <th>Staff Income Tax</th>
                <th>Grade</th>
                <th>Salary Scale</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($salary_reports as $report)
        {{-- @dd($report) --}}
            <tr>
                <td>{{ @$i = $i + 1 }}</td>
                <td style="white-space: nowrap">{{ !empty($report->emp_id) ? $report->emp_id : ('EMP-'.$report->user_id) }}</td>
                <td>{{ $report->name.' '.$report->last_name }}</td>
                <td>{{ $report->dept_name }}</td>
                <td>{{ $report->desi_name }}</td>
                {{-- <td>{{ $report->h_rent }}</td>
                <td>{{ $report->m_allow }}</td>
                <td>{{ $report->basic_salary }}</td> --}}
                <td>{{ $report->g_salary }}</td>
                <td>{{ $report->tax_deduct }}</td>
                <td>{{ $report->grade }}</td>
                <td>{{ $report->starting_salary.'-'.$report->end_salary }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Add SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportExcel() {
    let table = document.querySelector(".table");
    let wb = XLSX.utils.table_to_book(table, { sheet: "Tax Report" });
    let ws = wb.Sheets["Tax Report"];
    // Add thin border to all cells
    const range = XLSX.utils.decode_range(ws['!ref']); // get range of sheet
    for (let R = range.s.r; R <= range.e.r; ++R) {
        for (let C = range.s.c; C <= range.e.c; ++C) {
            let cell_address = { c: C, r: R };
            let cell_ref = XLSX.utils.encode_cell(cell_address);
            if (!ws[cell_ref]) ws[cell_ref] = { t: "s", v: "" }; // create empty cell if missing
            ws[cell_ref].s = {
                border: {
                    top: { style: "thin", color: { auto: 1 } },
                    bottom: { style: "thin", color: { auto: 1 } },
                    left: { style: "thin", color: { auto: 1 } },
                    right: { style: "thin", color: { auto: 1 } }
                }
            };
        }
    }

    // Export Excel
    XLSX.writeFile(wb, "tax_report.xlsx");
}
</script>


