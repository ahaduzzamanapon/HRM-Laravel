@extends('layouts.default')

@section('title')
Movement Details @parent
@stop

@section('content')
<section class="content-header">
    <h1>Movement Details</h1>
</section>

<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Movement Details Fields -->
                <div class="col-md-12">
                    <p><b>User:</b> {{ $movement->user->name ?? 'N/A' }}</p>
                    <p><b>From:</b> {{ $movement->from_location }}</p>
                    <p><b>To:</b> {{ $movement->to_location }}</p>
                    <p><b>Distance:</b> {{ $movement->distance }}</p>
                    <p><b>Reason:</b> {{ $movement->reason }}</p>
                    <p><b>TA Amount:</b> {{ $movement->ta_amount }}</p>
                    <p><b>DA Amount:</b> {{ $movement->da_amount }}</p>
                    <p><b>Total Amount:</b> {{ $movement->total_amount }}</p>
                    <p><b>Status:</b> {{ $movement->status }}</p>
                    <p><b>Approved By:</b> {{ $movement->approver->name ?? 'N/A' }}</p>
                    <p><b>Approved At:</b> {{ $movement->approved_at ? $movement->approved_at->format('Y-m-d H:i') : 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('movements.index') }}" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
@endsection