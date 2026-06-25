@forelse($jobs as $job)
    <div class="col-md-6 mb-4">
        <div class="card career-card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge badge-corporate px-3 py-2">{{ strtoupper($job->employment_type) }}</span>
                <small class="text-muted"><i class="fa fa-calendar-alt mr-1"></i> Posted {{ $job->created_at->format('M d, Y') }}</small>
            </div>
            <h4 class="job-title mb-2">{{ $job->title }}</h4>
            <p class="text-muted mb-4" style="font-weight: 600;">
                <i class="fa fa-map-marker-alt text-primary mr-2"></i> {{ $job->location ?? 'Multiple Locations' }}
                @if($job->experience_level)
                    <span class="mx-2">|</span> <i class="fa fa-user-tie text-primary mr-1"></i> {{ $job->experience_level }}
                @endif
            </p>
            
            <p class="text-secondary mb-4" style="line-height: 1.6;">
                {{ Str::limit(strip_tags($job->description), 130) }}
            </p>
            
            <div class="mt-auto text-right">
                <a href="{{ route('careers.details', $job->slug) }}" class="btn btn-corporate px-4 py-2">
                    View Position <i class="fa fa-chevron-right ml-2" style="font-size: 0.8em;"></i>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <i class="fa fa-briefcase fa-4x mb-4 text-muted" style="opacity: 0.3;"></i>
        <h3 class="font-weight-bold" style="color: #003366;">No Vacancies Available</h3>
        <p class="text-secondary mt-3">Try adjusting your search criteria or check back later.</p>
    </div>
@endforelse

@if($jobs->hasPages())
    <div class="col-12 mt-4 d-flex justify-content-center">
        {{ $jobs->links('pagination::bootstrap-4') }}
    </div>
@endif
