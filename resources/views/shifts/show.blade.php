@extends('layouts.default')

@section('title')
Shift Details @parent
@stop

@section('content')
<section class="content-header">
    <h1>Shift Details</h1>
</section>

<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Shift Details Fields -->
                <div class="col-md-12">
                    <p><b>Shift Name:</b> {{ $shift->shift_name }}</p>
                    <p><b>Branch:</b> {{ $shift->branch->branch_name ?? 'N/A' }}</p>
                </div>
            </div>

            <h5 class="mt-4">Day-wise Details</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Late Start</th>
                            <th>Lunch Start</th>
                            <th>Lunch End</th>
                            <th>Weekend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shift->shiftDetails as $detail)
                            <tr>
                                <td>{{ $detail->day_of_week }}</td>
                                <td>{{ $detail->in_time ?? 'N/A' }}</td>
                                <td>{{ $detail->out_time ?? 'N/A' }}</td>
                                <td>{{ $detail->late_start_time ?? 'N/A' }}</td>
                                <td>{{ $detail->lunch_start_time ?? 'N/A' }}</td>
                                <td>{{ $detail->lunch_end_time ?? 'N/A' }}</td>
                                <td>{{ $detail->is_weekend ? 'Yes' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('shifts.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>
@endsection