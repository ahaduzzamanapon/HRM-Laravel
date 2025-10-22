@php
    // dd($general_reports);
    if (empty($general_reports) || count($general_reports) === 0) {
        echo "<p>No data found.</p>";
        return;
    }
@endphp

<style>
    #my-attendance-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        font-family: Arial, Helvetica, sans-serif;
    }
    #my-attendance-table th,
    #my-attendance-table td {
        border: 1px solid #080808;
        padding: 7px;
        font-size: 12px;
    }
    #my-attendance-table th {
        background-color: #c7e6f8;
        color: #2d2c2c;
        text-align: center;
    }
    #my-attendance-table tr:hover {
        background-color: #197ab2bf;
        color: #fff;
    }
    #my-attendance-table .text-center {
        text-align: center !important;
    }
    .absent {
        background: #ff0000a6;
        color: #fff;
    }
    .late {
        background: #c69e27;
        color: #fff;
    }
    .leave {
        background: #56a754;
        color: #fff;
    }
</style>


        <div style="display: flex; align-items: center;justify-content: center;">
            <img src="{{ asset('salary_logo.jpg') }}" alt="Company Logo" style="max-width: 50px; height: auto;">
            <div style="margin-left: 10px;">
                <h3>Palli Sanchay Bank</h3>
                <p style="line-height: 0px;">Head Office,Dhaka</p>
            </div>
        </div>
        <div style="text-align: center; margin: 10px 0;">
            <span style="font-size: 12px;">
                <strong>Employess {{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $filterType))) }}</strong>
            </span>
        </div>

    <div class="container">
        <table class="table table-bordered" id="my-attendance-table" style="width: 90%">
            <thead>
                <tr>
                    <th>Sl. NO.</th>
                    <th>Emp. Name</th>
                    <th>Emp. Id</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Joining Date</th>
                    <th>Gross Sal</th>
                    <th>Bank Name</th>
                    <th>Branch Name</th>
                    <th>Ac No.</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($general_reports as $data)
                @php
                    $department  = $data->department;
                    $designation = $data->designation;
                    $bank   = $data->banksetups;
                    // dd($desig);
                @endphp

                <tr>
                    <td class="text-center">{{ @$i = $i + 1 }}</td>
                    <td class="text-center">{{ $data->name.' '.$data->last_name ?? '-' }}</td>
                    <td class="text-center">{{ $data->emp_id ?? '-' }}</td>
                    <td class="text-center">{{ $department->name ?? '-' }}</td>
                    <td class="text-center">{{ $designation->desi_name ?? '-' }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($data->date_of_join)) ?? '-' }}</td>
                    <td class="text-center">{{ $data->gross_salary ?? '-' }}</td>
                    <td class="text-center">{{ $bank->bank_name ?? '-' }}</td>
                    <td class="text-center">{{ $bank->branch_name ?? '-' }}</td>
                    <td class="text-center">{{ $data->account_no ?? rand(123456789, 223456789) }}</td>
                    <td class="text-center">{{ $bank->address ?? '-' }}</td>
                    <td class="text-center">{{ $data->phone_number ?? '-' }}</td>
                    <td class="text-center">
                        <img src="{{ $data->image ? asset('/' . $data->image) : asset('images/user/sample.webp') }}" alt="Employee Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- <div style="page-break-after: always"></div> --}}


