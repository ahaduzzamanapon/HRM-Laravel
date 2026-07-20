@extends('layouts.default')

{{-- Page title --}}
@section('title')
Departed Employees @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
</section>

<!-- Main content -->
<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card" width="88vw;">
        <section class="card-header">
            <h5 class="card-title d-inline">Departed Employees (Left / Resign / Retired)</h5>
        </section>
        <div class="card-body table-responsive">
            <table class="table table-hover table-striped table_data" id="departures-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Emp Id</th>
                        <th>Name</th>
                        <th>Departure Status</th>
                        <th>Effective Date</th>
                        <th>Reason</th>
                        <th>Remarks</th>
                        <th>Document</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employeeDepartures as $key => $departure)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $departure->user->emp_id ?? 'N/A' }}</td>
                        <td>{{ $departure->user->name ?? 'N/A' }} {{ $departure->user->last_name ?? '' }}</td>
                        <td>
                            <span class="badge bg-{{ $departure->status == 'retired' ? 'primary' : ($departure->status == 'resign' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($departure->status) }}
                            </span>
                        </td>
                        <td>{{ $departure->effective_date }}</td>
                        <td>{{ $departure->reason ?? 'N/A' }}</td>
                        <td>{{ $departure->remarks ?? 'N/A' }}</td>
                        <td>
                            @if($departure->document)
                                <a href="{{ asset($departure->document) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                    <i class="im im-icon-File"></i> View
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <div class='btn-group'>
                                <a href="{{ route('users.edit', [$departure->user_id]) }}" class='btn btn-info btn-xs'>
                                    <i class="im im-icon-Pen"></i> Edit Profile
                                </a>
                                {!! Form::open(['route' => ['employeeDepartures.destroy', $departure->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this departure track record?')">
                                    <i class="im im-icon-Trash"></i> Delete Track
                                </button>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
