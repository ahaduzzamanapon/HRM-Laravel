@php
    if (empty($attendanceDatas) || count($attendanceDatas) == 0 || $attendanceDatas == null) {
        echo  "<p>No data found.</p>";
        exit;
    }
@endphp

<style>
    #my-attendance-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        font-family: Arial, Helvetica, sans-serif;
    }
    tr th {
        text-align: center !important;
    }

    #my-attendance-table th, #my-attendance-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    #my-attendance-table th {
        background-color: #f2f2f2;
    }
    #my-attendance-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #my-attendance-table tr:hover {
        background-color: #ddd;
    }
</style>
<div style="text-align: center; margin: 20px;">
    <h3>Daily Attendance Report</h3>
    <div>
        <strong>Date:</strong> {{ date('d F, Y',strtotime($date)) }}
        <p>[P = Present, A = Absent, L = Leave]</p>
    </div>
</div>
<table class="table table-bordered table-striped" id="my-attendance-table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Emp. Id</th>
            <th style="text-align: left">Emp. Name</th>
            <th>In Time</th>
            <th>Out Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendanceDatas as $data)
            {{-- @dd($data->user) --}}
            <tr>
                <td style="text-align: center">{{ @$i = $i + 1 }}</td>
                <td style="text-align: center">{{ $data->employee_id }}</td>
                <td style="text-align: left">{{ $data->user->name.' '.$data->user->last_name }}</td>
                <td style="text-align: center">{{ date('h:i:s a',strtotime($data->clock_in ))}}</td>
                <td style="text-align: center">{{ date('h:i:s a',strtotime($data->clock_out)) }}</td>
                <td style="text-align: center">{{ $data->status == "Present" ? "P" : ($data->status == "Absent" ? "A" : "-" ) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>