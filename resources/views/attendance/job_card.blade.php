@php
    // dd($job_card);
    if (empty($job_card) || count($job_card) === 0) {
        echo "<p>No data found.</p>";
        return;
    }

    $showTime = in_array($filterType, ['present', 'all']);
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

@foreach ($job_card as $employeeId => $records)
        <div style="display: flex; align-items: center;justify-content: center;">
            <img src="{{ asset('salary_logo.jpg') }}" alt="Company Logo" style="max-width: 50px; height: auto;">
            <div style="margin-left: 10px;">
                <h3>Palli Sanchay Bank</h3>
                <p style="line-height: 0px;">Head Office,Dhaka</p>
            </div>
        </div>
        <div style="text-align: center; margin: 10px 0;">
            <span style="font-size: 12px;">
                <strong>{{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $filterType))) }} Report from </strong> {{ date('d F, Y', strtotime($fromDate)) }} to {{ date('d F, Y', strtotime($toDate)) }}
            </span>
        </div>

    @php
        $employee = $records->first()->user;
    @endphp

    <div class="container">

        <table style="margin: 0 auto; width: 40%; font-size: 12px;"">
            <tbody>
                <tr>
                    <td><strong>Employee Name :</strong></td>
                    <td>{{ $employee->name ?? 'N/A' }}</td>
                    <td><strong>Emp ID :</strong></td>
                    <td>{{ $employee->emp_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Department :</strong></td>
                    <td>{{ $employee->name ?? 'N/A' }}</td>
                    <td><strong>Designtion:</strong></td>
                    <td>{{ $employeeId }}</td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered" id="my-attendance-table" style="width: 60%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Shift</th>
                    <th>Attendance Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDays = count($records);
                    $totalPresent = 0;
                    $totalAbsent = 0;
                    $totalLeave = 0;
                    $totalOff = 0;
                    $totalLate = 0;
                @endphp

                @foreach($records as $index => $data)
                    @php
                        $inTime  =( $data->status == "Off Day" || $data->status == "Leave" || $data->status =="Absent") ? "-":date('h:i A', strtotime($data->clock_in));
                        $outTime =( $data->status == "Off Day" || $data->status == "Leave" || $data->status =="Absent") ? "-":date('h:i A', strtotime($data->clock_out));
                        $status = $data->status;

                        // Count status types
                        if ($status === 'Present') $totalPresent++;
                        elseif ($status === 'Absent') $totalAbsent++;
                        elseif ($status === 'Leave') $totalLeave++;
                        elseif ($status === 'Off Day') $totalOff++;

                        // Late count
                        if (!empty($data->late_status) && $data->late_status === 1) {
                            $totalLate++;
                        }
                    @endphp

                    <tr class="{{ $status == 'Absent' ? 'absent' : ($data->late_status == 1 && $status == 'Present' ? 'late' : ($status == 'Leave' ? 'leave' : '')) }}">
                        <td class="text-center">{{ date('d-M-y', strtotime($data->attendance_date)) }}</td>
                        <td class="text-center">{{ date('l', strtotime($data->attendance_date)) }}</td>
                        <td class="text-center">{{ $inTime }}</td>
                        <td class="text-center">{{ $outTime }}</td>
                        <td class="text-center">{{ $data->shift_name ?? '-' }}</td>
                        <td class="text-center">
                            {{ $status == 'Present' ? 'P' : ($status == 'Absent' ? 'A' : ($status == 'Leave' ? 'L' : ($status == 'Off Day' ? 'Off Day' : '-'))) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table table-bordered" id="my-attendance-table" style="width: 60%">
            <tbody>
                <tr>
                    <th>Total Days</th>
                    <th>Total Present</th>
                    <th>Total Absent</th>
                    <th>Total Leave</th>
                    <th>Total Off Days</th>
                    <th>Total Late</th>
                </tr>
                <tr>
                    <td class="text-center">{{ $totalDays }}</td>
                    <td class="text-center">{{ $totalPresent }}</td>
                    <td class="text-center">{{ $totalAbsent }}</td>
                    <td class="text-center">{{ $totalLeave }}</td>
                    <td class="text-center">{{ $totalOff }}</td>
                    <td class="text-center">{{ $totalLate }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="page-break-after: always"></div>
@endforeach

