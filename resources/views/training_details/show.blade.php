@extends('layouts.default')

@section('title')
Training Detail @parent
@stop

@section('content')
<section class="content-header">
    <h1>Training Detail</h1>
</section>

<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Training Detail Fields -->
                <div class="col-md-12">
                    <p><b>Training Name:</b> {{ $trainingDetail->training_name }}</p>
                    <p><b>Training Provider:</b> {{ $trainingDetail->training_provider }}</p>
                    <p><b>Training Type:</b> {{ $trainingDetail->training_type }}</p>
                    <p><b>Start Date:</b> {{ $trainingDetail->start_date }}</p>
                    <p><b>End Date:</b> {{ $trainingDetail->end_date }}</p>
                    <p><b>Description:</b> {{ $trainingDetail->description }}</p>
                    <p><b>Document:</b> <a href="{{ asset($trainingDetail->document) }}" target="_blank">View Document</a></p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('trainingDetails.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>
@endsection