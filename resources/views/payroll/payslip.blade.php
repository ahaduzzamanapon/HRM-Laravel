<style>
/* Container */
.payslip-container {
    width: 700px;
    margin: 20px auto;
    padding: 20px;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
}

/* Header */
.payslip-header {
    text-align: center;
    margin-bottom: 20px;
}
.payslip-header img {
    width: 80px;
    height: 80px;
    object-fit: contain;
}
.payslip-header h2 {
    margin: 5px 0 2px;
    font-size: 22px;
}
.payslip-header p {
    margin: 2px 0;
    font-size: 12px;
}

/* Employee Info */
.employee-info, .salary-info {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}
.employee-info td, .salary-info td {
    padding: 6px 10px;
    border: 1px solid #ccc;
}
.employee-info td:first-child, .salary-info td:first-child {
    font-weight: bold;
    width: 30%;
}

/* Section Titles */
.section-title {
    font-weight: bold;
    margin: 10px 0;
    font-size: 16px;
    text-decoration: underline;
}

/* Salary Table */
.salary-table {
    width: 100%;
    border-collapse: collapse;
}
.salary-table th, .salary-table td {
    padding: 8px 10px;
    border: 1px solid #ccc;
    text-align: right;
}
.salary-table th {
    background-color: #2c3e50;
    color: #fff;
}
.salary-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}
.salary-table tfoot td {
    font-weight: bold;
    background-color: #f1c40f;
    color: #2c3e50;
}

/* Footer */
.payslip-footer {
    text-align: center;
    margin-top: 15px;
    font-size: 12px;
    color: #777;
}
</style>
{{-- @dd($salary_reports) --}}
@foreach ($salary_reports as $report)
<div class="payslip-container">
    <div class="payslip-header">
        {{-- <img src="logo.png" alt="Company Logo"> --}}
        <h2>Company Name</h2>
        <p>Dhaka-1207</p>
        <h3>Payslip for the month of {{ \Carbon\Carbon::parse($report->salary_month)->format('F, Y') }}</h3>
    </div>

    <table class="employee-info">
        <tr>
            <td>Name</td>
            <td>{{ $report->name }}</td>
            <td>Employee ID</td>
            <td>{{ $report->emp_status }}</td>
        </tr>
        <tr>
            <td>Designation</td>
            <td>{{ $report->desig_id }}</td>
            <td>Department</td>
            <td>{{ $report->dept_id }}</td>
        </tr>
        <tr>
            <td>Salary Month</td>
            <td>{{ \Carbon\Carbon::parse($report->salary_month)->format('F, Y') }}</td>
            <td>Number of Days</td>
            <td>{{ $report->n_days }}</td>
        </tr>
        <tr>
            <td>Present</td>
            <td>{{ $report->present }}</td>
            <td>Absent</td>
            <td>{{ $report->absent }}</td>
        </tr>
        <tr>
            <td>Leave</td>
            <td>{{ $report->leave }}</td>
            <td>LOP</td>
            <td>{{ $report->absent_deduct }}</td>
        </tr>
    </table>

    <div class="section-title">Earnings & Deductions</div>
    <table class="salary-table">
        <thead>
            <tr>
                <th>Earnings</th>
                <th>Amount</th>
                <th>Deductions</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic</td>
                <td>{{ number_format($report->b_salary, 2) }}</td>
                <td>PF Deduction</td>
                <td>{{ number_format($report->pf_deduct, 2) }}</td>
            </tr>
            <tr>
                <td>Gross Salary</td>
                <td>{{ number_format($report->g_salary, 2) }}</td>
                <td>Loan Deduction</td>
                <td>{{ number_format($report->loan_deduct, 2) }}</td>
            </tr>
            <tr>
                <td>Total Allowances</td>
                <td>{{ number_format((int)$report->all_allows, 2) }}</td>
                <td>Other Deductions</td>
                <td>{{ number_format($report->others_deduct, 2) }}</td>
            </tr>
            <tr>
                <td>Payable Salary</td>
                <td>{{ number_format($report->pay_salary, 2) }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
@endforeach

