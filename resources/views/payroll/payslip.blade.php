<style>
/* Container */
.payslip-container {
    width: 700px;
    margin: 20px auto;
    padding: 20px;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    border: 1px solid #000000;
    border-radius: 0px;
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
    border: 1px solid #000000;
}
.employee-info td:first-child, .salary-info td:first-child {
    font-weight: bold;
    /* width: 30%; */
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
    padding: 5px 5px;
    border: 1px solid #000000;
    /* text-align: right; */
}
.salary-table th {
    background-color: #ffffff;
    color: #111111;
}


</style>
{{-- @dd($salary_reports) --}}
@foreach ($salary_reports as $report)
<div class="payslip-container">
    <div class="payslip-header">
        <div style="display: flex; align-items: center;justify-content: center;">
            <img src="{{ asset('salary_logo.jpg') }}" alt="Company Logo" style="max-width: 50px; height: auto;">
            <div style="margin-left: 10px;">
                <h3>Palli Sanchay Bank</h3>
                <p style="line-height: 0px;">Head Office,Dhaka</p>
            </div>
        </div>
        <div>
            <p style="margin-left: 25px;">Red Crescent Borak Tower (Level- 7,8,9 & 10)</p>
            <p style="margin-left: 25px;">37/3/A Escaton Garden Road, Dhaka-1000</p>
        </div>
        <div>
            <p style="text-decoration: underline;font-weight: bold;">Budget And Accounts Department</p>
            <h4 style="margin: 0px;margin-top:10px;margin-bottom:5px">Salary Slip/Pay Slip</h4>
            <hr style="margin: 0px;border: 1px solid black;">
        </div>
    </div>

    <table class="employee-info" style="font-size: 12px;">
        <tr>
            <td style="font-weight:bold">EMP ID</td>
            <td>{{ 'EMP-'.$report->user_id }}</td>
            <td style="font-weight:bold">EMP Name</td>
            <td>{{ $report->name.' '.$report->last_name }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold">Designation</td>
            <td>{{ $report->desi_name }}</td>
            <td style="font-weight:bold">Month/Year</td>
            <td>{{ date('F, Y', strtotime($report->salary_month)) }}</td>
        </tr>
        <tr>
            <td style="width: 170px;font-weight:bold">Bank Accounts No.</td>
            <td style="width: 160px;">{{ $report->account_no }}</td>
            <td style="font-weight:bold">Bank & Br. Name</td>
            <td>{{ $report->bank_name .', '.$report->branch_name }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold">Grade</td>
            <td>{{ $report->grade }}</td>
            <td style="font-weight:bold">Salary Scale</td>
            <td>{{ $report->starting_salary.'-'.$report->end_salary }}</td>
        </tr>
    </table>

    <table class="salary-table" style="font-size: 12px;">
        <thead>
            <tr>
                <th colspan="2" style="text-align: center">Earnings</th>
                <th colspan="2" style="text-align: center">Deductions</th>
            </tr>
            <tr>
                <th></th>
                <th style="text-align: left">Monthly</th>
                <th></th>
                <th style="text-align: left">Monthly</th>
            </tr>
        </thead>
        <tbody >
            <tr style="text-align: left">
                <td>Basic Officer</td>
                <td>{{ $report->emp_type !== "Stuff" ? number_format($report->b_salary, 2) : '' }}</td>
                <td>P F Bank Contribution Deduction 8.33%</td>
                <td>{{ "-" }}</td>
            </tr>
            <tr style="text-align: left">
                <td>Basic Staff</td>
                <td>{{ $report->emp_type == "Stuff" ? number_format($report->b_salary, 2) : '' }}</td>
                <td>Staff Income Tax</td>
                <td>{{ "-" }}</td>

            </tr>
            <tr style="text-align: left">
                <td>House Rent</td>
                <td>{{ number_format((int)$report->h_rent, 2) }}</td>
                <td>Benevolent Fund</td>
                <td>{{ "-" }}</td>

            </tr>
            <tr style="text-align: left">
                <td>Medical Allowance</td>
                <td>{{ number_format($report->m_allow, 2) }}</td>
                <td>Employees Contribution To P F Deduction</td>
                <td>{{ "-" }}</td>
            </tr>
            <tr style="text-align: left">
                <td style="width: 180px;">Special Benefit, 10%  <br>or 15% on Basic or Mini 1500/-</td>
                <td style="width: 170px;"></td>
                <td>Motorcycle/Car Loan</td>
                <td></td>
            </tr>
            <tr style="text-align: left">
                <td>PF Bank Contribution</td>
                <td>{{ "-" }}   </td>
                <td>House Building Loan Instalment</td>
                <td></td>
            </tr>
            <tr style="text-align: left">
                <td>Child Allowance</td>
                <td>{{ number_format($report->child_allow, 2) }}</td>
                <td>Staff Personal Loan</td>
                <td>{{ "-" }}</td>
            </tr>
            <tr style="text-align: left">
                <td>Transport Allowance</td>
                <td>{{ number_format($report->trans_allow, 2) }}</td>
                <td>Vehicle Fare</td>
                <td>{{ "-" }}</td>
            </tr>
            <tr style="text-align: left">
                <td></td>
                <td></td>
                <td>Stamp</td>
                <td>10</td>
            </tr>
            <tr>
                <td>Total Earning</td>
                <td>{{ number_format($report->net_salary, 2) }}</td>
                <td>Total Deduction</td>
                <td>{{ number_format($report->total_deduct, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @php
        $numberToWords = new \NumberToWords\NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $pay = isset($report->net_salary) ? $report->net_salary : 0;

    @endphp

    <table class="salary-table" style="font-size: 12px;margin-top: 10px;">
        <tr>
            <td style="text-align: left;width: 180px; font-weight: bold; padding-top: 10px;">Net Salary: </td>
            <td style="text-align: right; font-weight: bold; padding-top: 10px;">{{ number_format($report->net_salary, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: left;width: 180px; font-weight: bold; padding-top: 10px;">In Words: </td>
            <td style="text-align: right; font-weight: bold; padding-top: 10px;">{{ ucwords($numberTransformer->toWords($pay)) }} Taka Only</td>
        </tr>
    </table>


    <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 10px;">
        <p style="font-weight: bold">Salary Paid By</p>
        <p style="line-height: 0px;"><input type="checkbox">Cash</p>
        <p style="line-height: 0px;"><input type="checkbox">Bank</p>
        <p style="line-height: 0px;"><input type="checkbox">Cash and Bank Both</p>

    </div>


    <div style=" display: flex; justify-content: space-between; font-size: 12px;margin-top: 40px;">
        <div style="text-align: center;">
            <p style="line-height: 0px;border:1px solid black"></p>
            <p style="line-height: 0px;">Prepared By</p>
            <p style="line-height: 0px;">Budget And Accounts Department</p>
        </div>
        <div style="text-align: center;">
            <p style="line-height: 0px;border:1px solid black"></p>
            <p style="line-height: 0px;">Authorized Signature</p>
            <p style="line-height: 0px;">Budget And Accounts Department</p>
        </div>

    </div>
</div>
@endforeach

