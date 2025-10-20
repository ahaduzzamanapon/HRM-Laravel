@php
    // dd($attendanceDatas);
    if (empty($attendanceDatas) || count($attendanceDatas) === 0) {
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
        border: 1px solid #ddd;
        padding: 7px;
        font-size: 14px;
    }

    #my-attendance-table th {
        background-color: #c7e6f8;
        color: #2d2c2c;
        text-align: center;
    }

    #my-attendance-table tr:hover {
        background-color: #0177bcb6;
        color: #fff;
    }
    #my-attendance-table .text-center {
        text-align: center !important;
    }
    .absent {
        background: #ff0000a6;
        color: #fff;
    }
</style>

<div style="text-align: center; margin: 20px 0;">
    <h3 style="margin-bottom: 10px;">Daily Attendance Report [ {{ ucfirst($filterType) }} ]</h3>
    <div style="font-size: 16px;">
        <strong>Date:</strong> {{ date('d F, Y', strtotime($date)) }}
        @if($filterType === 'all')
            <p style="margin: 5px 0;">[P = Present, A = Absent, L = Leave]</p>
        @endif
    </div>
</div>


<table class="table table-bordered" id="my-attendance-table" style="width: 80%">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Emp. Id</th>
            <th>Emp. Name</th>

            @if($showTime)
                <th>In Time</th>
                <th>Out Time</th>
            @endif

            @if($filterType === 'all')
                <th>Status</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @foreach($attendanceDatas as $index => $data)
            @php
                $isAbsent = $data->status === 'Absent';
                $user = $data->user ?? null;
            @endphp

            <tr class="{{ $filterType === 'all' && $isAbsent ? 'absent' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $data->employee_id }}</td>
                <td>{{ optional($user)->name }} {{ optional($user)->last_name }}</td>

                @if($showTime)
                    <td class="text-center">{{ $isAbsent ? '-' : date('h:i:s a', strtotime($data->clock_in)) }}</td>
                    <td class="text-center">{{ $isAbsent ? '-' : date('h:i:s a', strtotime($data->clock_out)) }}</td>
                @endif

                @if($filterType === 'all')
                    <td class="text-center">
                        @switch($data->status)
                            @case('Present') P @break
                            @case('Absent')  A @break
                            @case('Leave')   L @break
                            @default         -
                        @endswitch
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
