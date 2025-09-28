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
    scale: 1;
    zoom: 90%;
}

/* Table Head */
.table thead th {
    background-color: #ffffff; /* Bootstrap primary color */
    color: #000000;
    font-weight: bold;
    text-align: center;
    padding: 4px;
    border: 1px solid #000;
    font-size:9px;
}

/* Table Body */
.table tbody td {
    text-align: center;
    padding: 6px 8px;
    border: 1px solid #000;
    font-size:9px;

}


/* Responsive table */
@media (max-width: 768px) {
    .table thead th, .table tbody td {
        font-size: 9px;
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
{{-- @dd($salary_month) --}}
<div class="table-responsive">
    <div class="payslip-header">
        <div style="display: flex; align-items: center;justify-content: center;">
            <img src="{{ asset('salary_logo.jpg') }}" alt="Company Logo" style="max-width: 50px; height: auto;">
            <div style="margin-left: 10px;">
                <h3>Palli Sanchay Bank</h3>
            <p style="line-height: 0px;">Head Office,Dhaka</p>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; align-items: center;justify-content: center; line-height: 0px;">
            <h5>প্রধান কার্যালয়ে কর্মরত ২৬৪ জন কর্মকর্তা-কর্মচারীদের {{ \Carbon\Carbon::parse($salary_month)->format('F, Y') }} মাসের বেতন-ভাতাদির বিবরণীঃ</h5>
            <h5 style="margin-left: 954px;position: absolute;line-height: 0px;">পতাকা-ক</h5>
        </div>
    </div>
    <table class="table" id="DataTable">
        <thead>
            <tr>
                <th>SL NO</th>
                <th>ID NO</th>
                <th>EMP Name</th>
                <th>Dsg.</th>
                <th>Basic Officer</th>
                <th>Basic Staff</th>
                <th>House Rent</th>
                <th>Medical Allowance</th>
                <th>Special Benefit, 10% or 15% on Basic or Mini 1500/-</th>
                <th>PF Bank Contribution 8.33%</th>
                <th>Child Allowance</th>
                <th>Transport Allowance</th>
                <th>Gross Earnings</th>
                <th>PF Bank Contribution 8.33%</th>
                <th>Staff Income Tax</th>
                <th>Benevolent Fund</th>
                <th>Employees Contribution To P F Deduction</th>
                <th>Motorcycle/Car Loan</th>
                <th>House Building Loan Instalment</th>
                <th>Staff Personal Loan</th>
                <th>Vehicle Fare</th>
                <th>Stamp</th>
                <th>Gross Deduction</th>
                <th>Net Salary</th>
                <th>Ac No.</th>
                <th>Bank Name</th>
                <th>Routing No</th>
                <th>Grade</th>
                <th>Salary Scale</th>
            </tr>
            <tr style="font-size:9px;text-align:center;">
                <td style="border:1px solid #000;"></td>
                <td style="border:1px solid #000;">1</td>
                <td style="border:1px solid #000;">2</td>
                <td style="border:1px solid #000;">3</td>
                <td style="border:1px solid #000;">4</td>
                <td style="border:1px solid #000;">5</td>
                <td style="border:1px solid #000;">6</td>
                <td style="border:1px solid #000;">7</td>
                <td style="border:1px solid #000;">8</td>
                <td style="border:1px solid #000;">9</td>
                <td style="border:1px solid #000;">10</td>
                <td style="border:1px solid #000;">11</td>
                <td style="border:1px solid #000;">12</td>
                <td style="border:1px solid #000;">13</td>
                <td style="border:1px solid #000;">14</td>
                <td style="border:1px solid #000;">15</td>
                <td style="border:1px solid #000;">16</td>
                <td style="border:1px solid #000;">17</td>
                <td style="border:1px solid #000;">18</td>
                <td style="border:1px solid #000;">19</td>
                <td style="border:1px solid #000;">20</td>
                <td style="border:1px solid #000;">21</td>
                <td style="border:1px solid #000;">22</td>
                <td style="border:1px solid #000;">23</td>
                <td style="border:1px solid #000;">24</td>
                <td style="border:1px solid #000;">25</td>
                <td style="border:1px solid #000;">26</td>
                <td style="border:1px solid #000;">27</td>
                <td style="border:1px solid #000;">28</td>
            </tr>
        </thead>
        <tbody>
        @foreach ($salary_reports as $report)
            {{-- @dd($report) --}}
            <tr>

                <td>{{ @$i = $i + 1 }}</td>
                <td style="white-space: nowrap">{{ "Emp-".$report->user_id }}</td>
                <td>{{ $report->name }}</td>
                <td>{{ $report->present }}</td>
                <td>{{ $report->absent }}</td>
                <td>{{ $report->leave }}</td>
                <td>{{ $report->weekend }}</td>
                <td>{{ $report->holiday }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->pay_day }}</td>
                <td>{{ $report->b_salary == null ? 12345 : number_format($report->b_salary, 2) }}</td>
                <td>{{ $report->g_salary == null ? 12345 : number_format($report->g_salary, 2) }}</td>
                <td>{{ $report->pay_salary == null ? 12345 : number_format($report->pay_salary, 2) }}</td>
                <td>{{ $report->total_allow == null ? 12345 : number_format($report->total_allow, 2) }}</td>
                <td>{{ $report->absent_deduct == null ? 12345 : number_format($report->absent_deduct, 2) }}</td>
                <td>{{ $report->loan_deduct == null ? 12345 : number_format($report->loan_deduct, 2) }}</td>
                <td>{{ $report->pf_deduct == null ? 12345 : number_format($report->pf_deduct, 2) }}</td>
                <td>{{ $report->others_deduct == null ? 12345 : number_format($report->others_deduct, 2) }}</td>
                <td>{{ $report->total_deduct == null ? 12345 : number_format($report->total_deduct, 2) }}</td>
                <td>{{ $report->net_salary== null ? 12345 : number_format($report->net_salary, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
