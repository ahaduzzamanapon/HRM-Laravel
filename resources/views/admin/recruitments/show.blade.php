@extends('layouts.default')

@section('title')
Job Post Details @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Job Post Details</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-default" href="{{ route('recruitments.index') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 mb-3">
                    <h3 class="text-primary">{{ $recruitment->title }}</h3>
                    <p class="text-muted mb-0">
                        <i class="fa fa-map-marker"></i> {{ $recruitment->location ?? 'Anywhere / Remote' }} | 
                        <i class="fa fa-briefcase"></i> {{ $recruitment->employment_type }}
                    </p>
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Status:</strong>
                    @if($recruitment->status == 'published')
                        <span class="badge badge-success px-2 py-1">Published</span>
                    @elseif($recruitment->status == 'draft')
                        <span class="badge badge-secondary px-2 py-1">Draft</span>
                    @else
                        <span class="badge badge-danger px-2 py-1">Closed</span>
                    @endif
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Deadline:</strong>
                    {{ $recruitment->deadline ? $recruitment->deadline->format('M d, Y') : 'No Deadline' }}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Experience Level:</strong>
                    {{ $recruitment->experience_level ?? 'Not specified' }}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Salary Range:</strong>
                    @if($recruitment->salary_range_start && $recruitment->salary_range_end)
                        ৳{{ number_format($recruitment->salary_range_start, 2) }} - ৳{{ number_format($recruitment->salary_range_end, 2) }}
                    @elseif($recruitment->salary_range_start)
                        From ৳{{ number_format($recruitment->salary_range_start, 2) }}
                    @elseif($recruitment->salary_range_end)
                        Up to ৳{{ number_format($recruitment->salary_range_end, 2) }}
                    @else
                        Not specified
                    @endif
                </div>

                <div class="col-sm-12 mt-4">
                    <h5>Description</h5>
                    <div class="p-3 bg-light rounded border">
                        {!! $recruitment->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
