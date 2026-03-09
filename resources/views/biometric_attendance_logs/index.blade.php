@extends('layouts.default')
@section('title')
Biometric Attendance Logs
@parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Biometric Attendance Logs</h1>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        <div class="card mb-3">
            <div class="card-body">
                {!! Form::open(['route' => 'biometricAttendanceLogs.index', 'method' => 'GET', 'class' => 'form-inline']) !!}
                <div class="form-group mr-2">
                    {!! Form::label('date', 'Date:', ['class' => 'mr-2']) !!}
                    {!! Form::date('date', request('date'), ['class' => 'form-control']) !!}
                </div>
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
                <a href="{{ route('biometricAttendanceLogs.index') }}" class="btn btn-secondary mt-4 ml-2">Clear</a>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="card">
            <section class="card-header">
                <h5 class="card-title d-inline">Device Logs</h5>
            </section>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center" id="biometricAttendanceLogs-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Biometric User ID</th>
                                    <th>Device</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $key => $log)
                                    @php
                                        $user = $users[$log->biometric_user_id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $logs->firstItem() + $key }}</td>
                                        <td>
                                            @if($user)
                                                {{ $user->name }} {{ $user->last_name }} <br>
                                                <small class="text-muted">{{ $user->designation->desi_name ?? '' }}</small>
                                            @else
                                                <span class="text-danger">Unmapped (Assign ID in Employees Mapping)</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $log->biometric_user_id }}</strong></td>
                                        <td>{{ $log->device->name ?? 'Unknown' }} ({{ $log->device->serial_number ?? '' }})</td>
                                        <td>{{ \Carbon\Carbon::parse($log->timestamp)->format('Y-m-d h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No attendance logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection