@extends('layouts.default')
@section('title')
Biometric Employee Mappings
@parent
@stop

@section('content')
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h1>Biometric Employee Mappings</h1>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')
        @include('adminlte-templates::common.errors')

        <div class="card mb-3">
            <div class="card-body">
                {!! Form::open(['route' => 'biometricEmployeeMappings.index', 'method' => 'GET', 'class' => 'form-inline']) !!}
                <div class="form-group mr-2">
                    {!! Form::text('search', request('search'), ['class' => 'form-control', 'placeholder' => 'Search by Name, Email, or Bio ID']) !!}
                </div>
                <button type="submit" class="btn btn-primary mt-4">Search</button>
                <a href="{{ route('biometricEmployeeMappings.index') }}" class="btn btn-secondary mt-4 ml-2">Clear</a>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="card">
            <section class="card-header">
                <h5 class="card-title d-inline">Employee List</h5>
            </section>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Details</th>
                                    <th>Department & Desig.</th>
                                    <th>Current Biometric ID</th>
                                    <th>Assign Biometric ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td class="align-middle">{{ $users->firstItem() + $key }}</td>
                                        <td class="align-middle text-left">
                                            <strong>{{ $user->name }} {{ $user->last_name }}</strong><br>
                                            <small class="text-muted">{{ $user->email }}</small><br>
                                            <small class="badge bg-info">Punch ID: {{ $user->punch_id ?? 'N/A' }}</small>
                                        </td>
                                        <td class="align-middle">
                                            {{ $user->department->name ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ $user->designation->desi_name ?? 'N/A' }}</small>
                                        </td>
                                        <td class="align-middle">
                                            @if($user->biometric_id)
                                                <span class="badge bg-success"
                                                    style="font-size:14px;">{{ $user->biometric_id }}</span>
                                            @else
                                                <span class="badge bg-secondary">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            {!! Form::open(['route' => ['biometricEmployeeMappings.update', $user->id], 'method' => 'patch', 'class' => 'form-inline justify-content-center']) !!}
                                            <div class="input-group">
                                                {!! Form::text('biometric_id', $user->biometric_id, ['class' => 'form-control form-control-sm', 'placeholder' => 'Enter Bio ID from Device', 'style' => 'max-width: 150px;']) !!}
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
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
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection