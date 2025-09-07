@extends('layouts.default')

@section('title')
Shift Management @parent
@stop

@section('content')
<section class="content-header">
    <h1>Shift Management</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Shifts</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('shifts.create') }}">Add New Shift</a>
            </div>
        </div>
        <div class="card-body">
            @if($shifts->isEmpty())
                <p class="text-center">No shifts found.</p>
            @else
                @foreach($shifts as $shift)
                    <div class="card mb-4">
                        <div class="card-header text-white">
                            <h4 class="mb-0">{{ $shift->shift_name }} (Branch: {{ $shift->branch->branch_name ?? 'N/A' }})</h4>
                        </div>
                        <div class="card-body">
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
                            <div class="mt-3">
                                <a href="{{ route('shifts.edit', [$shift->id]) }}" class='btn btn-info btn-sm'>Edit Shift</a>
                                {!! Form::open(['route' => ['shifts.destroy', $shift->id], 'method' => 'delete', 'class' => 'd-inline']) !!}
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this shift?')">Delete Shift</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection