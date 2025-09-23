<style>
/* Container */
.table-responsive {
    width: 100%;
    margin: 20px auto;
    padding: 10px;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #ccc;
}

/* Table Header */
.table thead th {
    background-color: #2c3e50; /* Dark header */
    color: #ecf0f1; /* White text */
    font-weight: bold;
    text-align: center;
    padding: 8px;
    border: 1px solid #ccc;
}

/* Table Body */
.table tbody td {
    text-align: center;
    padding: 6px 8px;
    border: 1px solid #ccc;
}

/* Zebra Striping */
.table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

/* Hover Effect */
.table tbody tr:hover {
    background-color: #dff0d8;
}

/* Payslip Title */
.payslip-title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
}

/* Highlight Totals Row */
.table tfoot td {
    font-weight: bold;
    background-color: #f1c40f;
    color: #2c3e50;
}

/* Responsive for small screens */
@media (max-width: 768px) {
    .table thead th, .table tbody td {
        font-size: 12px;
        padding: 4px;
    }
    .payslip-title {
        font-size: 18px;
    }
}
</style>


<div class="table-responsive">
<div class="payslip-title">Salary Payslip</div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Salary Month</th>
                <th>Days</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Leave</th>
                <th>Weekend</th>
                <th>Holiday</th>
                <th>Pay Day</th>
                <th>Basic Salary</th>
                <th>Gross Salary</th>
                <th>Pay Salary</th>
                <th>Total Allow</th>
                <th>All Allowances</th>
                <th>Absent Deduct</th>
                <th>Loan Deduct</th>
                <th>PF Deduct</th>
                <th>Others Deduct</th>
                <th>Total Deduct</th>
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
</div>

