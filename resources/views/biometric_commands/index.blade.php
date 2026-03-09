@extends('layouts.default')
@section('title')
Biometric Commands
@parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Device Commands</h1>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="card">
            <section class="card-header">
                <h5 class="card-title d-inline">Command Queue</h5>
                <span class="float-right">
                    <a class="btn btn-primary pull-right" href="{{ route('biometricCommands.create') }}">Queue New
                        Command</a>
                </span>
            </section>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center" id="biometricCommands-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Device</th>
                                    <th>Command String</th>
                                    <th>Status</th>
                                    <th>Queued At</th>
                                    <th>Executed At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commands as $key => $command)
                                    <tr>
                                        <td>{{ $commands->firstItem() + $key }}</td>
                                        <td>{{ $command->device->name ?? 'N/A' }}
                                            <br><small>({{ $command->device->serial_number ?? '' }})</small></td>
                                        <td><code>{{ $command->command_string }}</code></td>
                                        <td>
                                            @if($command->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($command->status === 'sent')
                                                <span class="badge bg-primary">Sent</span>
                                            @elseif($command->status === 'executed')
                                                <span class="badge bg-success">Executed</span>
                                            @elseif($command->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($command->created_at)->format('Y-m-d h:i A') }}</td>
                                        <td>{{ $command->executed_at ? \Carbon\Carbon::parse($command->executed_at)->format('Y-m-d h:i A') : '-' }}
                                        </td>
                                        <td>
                                            @if($command->status === 'pending')
                                                                            {!! Form::open(['route' => ['biometricCommands.destroy', $command->id], 'method' => 'delete']) !!}
                                                                            <div class='btn-group'>
                                                                                {!! Form::button('<i class="im im-icon-Remove"></i> Cancel', [
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-outline-danger btn-xs',
                                                    'onclick' => "return confirm('Are you sure you want to cancel this command?')"
                                                ]) !!}
                                                                            </div>
                                                                            {!! Form::close() !!}
                                            @else
                                                <span class="text-muted">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $commands->links() }}
            </div>
        </div>
    </div>
@endsection