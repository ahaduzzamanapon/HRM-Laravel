@extends('layouts.default')
@section('title')
Biometric Devices
@parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Biometric Devices</h1>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="card">
            <section class="card-header">
                <h5 class="card-title d-inline">Biometric Devices</h5>
                <span class="float-right">
                    <a class="btn btn-primary pull-right" href="{{ route('biometricDevices.create') }}">Add New Device</a>
                </span>
            </section>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center" id="biometricDevices-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Device Name</th>
                                    <th>Serial Number</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Last Online</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devices as $key => $device)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $device->name }}</td>
                                                                <td>{{ $device->serial_number }}</td>
                                                                <td>{{ $device->ip_address ?? 'N/A' }}</td>
                                                                <td>
                                                                    @if($device->last_active_at && $device->last_active_at->diffInMinutes(now()) < 5)
                                                                        <span class="badge bg-success">Online</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Offline</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $device->last_active_at ? $device->last_active_at->diffForHumans() : 'Never' }}
                                                                </td>
                                                                <td>
                                                                    {!! Form::open(['route' => ['biometricDevices.destroy', $device->id], 'method' => 'delete']) !!}
                                                                    <div class='btn-group'>
                                                                        {!! Form::button('<i class="im im-icon-Remove"></i>', [
                                        'type' => 'submit',
                                        'class' => 'btn btn-outline-danger btn-xs',
                                        'onclick' => "return confirm('Are you sure you want to delete this device?')"
                                    ]) !!}
                                                                    </div>
                                                                    {!! Form::close() !!}
                                                                </td>
                                                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $devices->links() }}
            </div>
        </div>
    </div>
@endsection