@extends('layouts.default')

@section('title')
Movements @parent
@stop

@section('content')
<section class="content-header">
    <h1>Movements</h1>
</section>

<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Movements</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('movements.create') }}">Add New Movement</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Distance</th>
                            <th>Reason</th>
                            <th>TA</th>
                            <th>DA</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                            <tr>
                                <td>{{ $movement->user->name ?? 'N/A' }}</td>
                                <td>{{ $movement->from_location }}</td>
                                <td>{{ $movement->to_location }}</td>
                                <td>{{ $movement->distance }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>{{ $movement->ta_amount }}</td>
                                <td>{{ $movement->da_amount }}</td>
                                <td>{{ $movement->total_amount }}</td>
                                <td>{{ $movement->status }}</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href="{{ route('movements.show', [$movement->id]) }}" class='btn btn-default btn-xs'>View</a>
                                        <a href="{{ route('movements.edit', [$movement->id]) }}" class='btn btn-default btn-xs'>Edit</a>
                                        {!! Form::open(['route' => ['movements.destroy', $movement->id], 'method' => 'delete', 'class' => 'd-inline']) !!}
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this movement?')">Delete</button>
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center">
                {!! $movements->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection