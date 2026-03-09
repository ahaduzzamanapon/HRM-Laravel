@extends('layouts.default')
@section('title')
Add Biometric Device
@parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Add Biometric Device</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>

    <div class="content">
        @include('adminlte-templates::common.errors')

        <div class="card">
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Note:</strong> Enter the exact Serial Number (SN) shown on the ZKTeco device. The system will
                    automatically detect its IP address when it comes online.
                </div>

                {!! Form::open(['route' => 'biometricDevices.store']) !!}

                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('name', 'Device Name (Optional):') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'e.g. Front Door Device']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('serial_number', 'Serial Number (Required):') !!}
                        {!! Form::text('serial_number', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'e.g. CDMQ123456789']) !!}
                    </div>

                    <div class="form-group col-sm-12 mt-3">
                        {!! Form::submit('Save Device', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('biometricDevices.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection