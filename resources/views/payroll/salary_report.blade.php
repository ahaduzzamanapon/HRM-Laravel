<style>
/* Table Container */
.table-responsive {
    margin: 20px auto;
    max-width: 100%;
    overflow-x: auto;
    font-family: Arial, sans-serif;
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #ccc;
    background-color: #fff;
}

/* Table Head */
.table thead th {
    background-color: #4c82bb; /* Bootstrap primary color */
    color: #fff;
    font-weight: bold;
    text-align: center;
    padding: 4px;
    border: 1px solid #ccc;
    font-size:12px;
}

/* Table Body */
.table tbody td {
    text-align: center;
    padding: 6px 8px;
    border: 1px solid #ccc;
    font-size:12px;

}

/* Zebra Striping for rows */
.table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Hover effect */
.table tbody tr:hover {
    background-color: #d1e7fd;
}

/* Responsive table */
@media (max-width: 768px) {
    .table thead th, .table tbody td {
        font-size: 12px;
        padding: 4px;
    }
    .table-responsive h3 {
        font-size: 16px;
        text-align: center;
    }
}
</style>

<div class="table-responsive">
    <h3 class="text-center">Salary Report</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department ID</th>
                <th>Designation ID</th>
                <th>Salary Month</th>
                <th>Number of Days</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Leave</th>
                <th>Weekend</th>
                <th>Holiday</th>
                <th>Pay Day</th>
                <th>Basic Salary</th>
                <th>Gross Salary</th>
                <th>Pay Salary</th>
                <th>Total Allowance</th>
                <th>All Allowances</th>
                <th>Absent Deduction</th>
                <th>Loan Deduction</th>
                <th>PF Deduction</th>
                <th>Others Deduction</th>
                <th>Total Deduction</th>
                <th>Net Salary</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($salary_reports as $report)
            <tr>
                <td>{{ $report->name }}</td>
                <td>{{ $report->dept_id }}</td>
                <td>{{ $report->desig_id }}</td>
                <td>{{ $report->salary_month }}</td>
                <td>{{ $report->n_days }}</td>
                <td>{{ $report->present }}</td>
                <td>{{ $report->absent }}</td>
                <td>{{ $report->leave }}</td>
                <td>{{ $report->weekend }}</td>
                <td>{{ $report->holiday }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->b_salary }}</td>
                <td>{{ $report->g_salary }}</td>
                <td>{{ $report->pay_salary }}</td>
                <td>{{ $report->total_allow }}</td>
                <td>{{ $report->all_allows }}</td>
                <td>{{ $report->absent_deduct }}</td>
                <td>{{ $report->loan_deduct }}</td>
                <td>{{ $report->pf_deduct }}</td>
                <td>{{ $report->others_deduct }}</td>
                <td>{{ $report->total_deduct }}</td>
                <td>{{ $report->net_salary }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
