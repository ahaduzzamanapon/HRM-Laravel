@extends('layouts.default')
@section('title')
Queue Device Command
@parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Queue Device Command</h1>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>

    <div class="content">
        @include('adminlte-templates::common.errors')

        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong>Note:</strong> Commands are queued and will be executed the next time the selected device polls
                    the server. Do not spam commands to a single device.
                </div>

                {!! Form::open(['route' => 'biometricCommands.store']) !!}

                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('biometric_device_id', 'Target Device:') !!}
                        {!! Form::select('biometric_device_id', $devices, null, ['class' => 'form-control', 'required' => true]) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('command_type', 'Command Type:') !!}
                        <select name="command_type" id="command_type" class="form-control" required>
                            <option value="">-- Select Command --</option>
                            <option value="REBOOT">Reboot Device</option>
                            <option value="CLEAR LOG">Clear All Attendance Logs</option>
                            <option value="CLEAR DATA">Clear All Data (Users + Logs)</option>
                            <option value="UNLOCK">Unlock Door</option>
                            <option value="CHECK">Check Status</option>
                            <option value="CUSTOM">Custom Command</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12" id="custom_command_wrapper" style="display: none;">
                        {!! Form::label('command_params', 'Custom Parameters:') !!}
                        {!! Form::text('command_params', null, ['class' => 'form-control', 'placeholder' => 'e.g. USER PIN=123 Name=John']) !!}
                        <small class="text-muted mt-1">Leave empty if not required by the command type.</small>
                    </div>

                    <div class="form-group col-sm-12 mt-3">
                        {!! Form::submit('Queue Command', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('biometricCommands.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#command_type').change(function () {
                    if ($(this).val() === 'CUSTOM') {
                        $('#custom_command_wrapper').show();
                    } else {
                        $('#custom_command_wrapper').hide();
                    }
                });
            });
        </script>
    @endpush
@endsection