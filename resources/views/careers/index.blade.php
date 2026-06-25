@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">Careers</h1>
            <p class="lead">Join our team and help us build the future.</p>
        </div>
    </div>

    <div class="row">
        @forelse($jobs as $job)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">{{ $job->title }}</h4>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="fa fa-map-marker"></i> {{ $job->location ?? 'Remote/Any' }}
                        </h6>
                        <p class="card-text mt-3">
                            {{ Str::limit($job->description, 120) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('careers.show', $job->slug) }}" class="btn btn-primary w-100">View Details & Apply</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    There are currently no open positions. Please check back later!
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
