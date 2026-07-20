@extends('layouts.default')

{{-- Page title --}}
@section('title')
Recruitment Dashboard @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Recruitment Dashboard</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <!-- Stat Cards -->
    <div class="row">
        <!-- Card 1: Total Job Openings -->
        <div class="col-md-3 col-sm-6 col-12 mb-3">
            <div class="card shadow-sm border-0 h-100" style="background-color: #e8f4fd; border-left: 4px solid #0177bc !important; border-radius: 8px;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase font-weight-bold mb-0 d-block" style="font-size: 10px; letter-spacing: 0.5px; color: #555 !important;">Total Job Openings</span>
                            <h3 class="mb-0 font-weight-bold" style="color: #0177bc; font-size: 24px; line-height: 1.2;">{{ $jobStats['total'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <i class="fa fa-briefcase" style="color: #0177bc; font-size: 14px;"></i>
                        </div>
                    </div>
                    <div class="mt-1 border-top pt-1" style="font-size: 11px; color: #666 !important;">
                        <span class="font-weight-bold text-success">{{ $jobStats['published'] }}</span> Published &bull; <span class="font-weight-bold text-warning">{{ $jobStats['draft'] }}</span> Draft
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Applications -->
        <div class="col-md-3 col-sm-6 col-12 mb-3">
            <div class="card shadow-sm border-0 h-100" style="background-color: #eafaf1; border-left: 4px solid #2ecc71 !important; border-radius: 8px;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase font-weight-bold mb-0 d-block" style="font-size: 10px; letter-spacing: 0.5px; color: #555 !important;">Total Applications</span>
                            <h3 class="mb-0 font-weight-bold" style="color: #27ae60; font-size: 24px; line-height: 1.2;">{{ $appStats['total'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <i class="fa fa-users" style="color: #27ae60; font-size: 14px;"></i>
                        </div>
                    </div>
                    <div class="mt-1 border-top pt-1" style="font-size: 11px; color: #666 !important;">
                        <span class="font-weight-bold text-info">{{ $appStats['reviewed'] }}</span> Reviewed &bull; <span class="font-weight-bold text-success">{{ $appStats['shortlisted'] }}</span> Shortlisted
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Pending Review (New) -->
        <div class="col-md-3 col-sm-6 col-12 mb-3">
            <div class="card shadow-sm border-0 h-100" style="background-color: #fef9e7; border-left: 4px solid #f1c40f !important; border-radius: 8px;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase font-weight-bold mb-0 d-block" style="font-size: 10px; letter-spacing: 0.5px; color: #555 !important;">Pending Review</span>
                            <h3 class="mb-0 font-weight-bold" style="color: #d35400; font-size: 24px; line-height: 1.2;">{{ $appStats['new'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <i class="fa fa-clock-o" style="color: #d35400; font-size: 14px;"></i>
                        </div>
                    </div>
                    <div class="mt-1 border-top pt-1 text-truncate" style="font-size: 11px; color: #d35400 !important;">
                        Requires recruitment action
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Rejected Applications -->
        <div class="col-md-3 col-sm-6 col-12 mb-3">
            <div class="card shadow-sm border-0 h-100" style="background-color: #f9ebea; border-left: 4px solid #e74c3c !important; border-radius: 8px;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase font-weight-bold mb-0 d-block" style="font-size: 10px; letter-spacing: 0.5px; color: #555 !important;">Rejected Applications</span>
                            <h3 class="mb-0 font-weight-bold" style="color: #c0392b; font-size: 24px; line-height: 1.2;">{{ $appStats['rejected'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <i class="fa fa-user-times" style="color: #c0392b; font-size: 14px;"></i>
                        </div>
                    </div>
                    <div class="mt-1 border-top pt-1" style="font-size: 11px; color: #666 !important;">
                        {{ number_format(($appStats['total'] > 0 ? ($appStats['rejected'] / $appStats['total']) * 100 : 0), 1) }}% of total
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Sections -->
    <div class="row mt-4">
        <!-- Left Column: Top Job Posts & Demand -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 font-weight-bold" style="color: #333 !important;"><i class="fa fa-briefcase mr-2 text-info"></i> Top Job Posts & Demand</h5>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-link text-info p-0 font-weight-bold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="color: #555 !important;">Job Title</th>
                                    <th style="color: #555 !important;">Status</th>
                                    <th style="color: #555 !important;">Deadline</th>
                                    <th class="text-center" style="color: #555 !important;">Applications</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobsWithAppCounts as $job)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="font-weight-bold" style="color: #0177bc !important; text-decoration: none;">
                                                {{ $job->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($job->status == 'published')
                                                <span class="badge badge-success">Published</span>
                                            @elseif($job->status == 'draft')
                                                <span class="badge badge-warning">Draft</span>
                                            @else
                                                <span class="badge badge-secondary">Closed</span>
                                            @endif
                                        </td>
                                        <td style="color: #444 !important;">
                                            {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : 'No Deadline' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info badge-pill px-2 py-1">{{ $job->applications_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No active job posts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Applications -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 font-weight-bold" style="color: #333 !important;"><i class="fa fa-user-plus mr-2 text-success"></i> Recent Applications</h5>
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-link text-success p-0 font-weight-bold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="color: #555 !important;">Applicant</th>
                                    <th style="color: #555 !important;">Job Applied</th>
                                    <th style="color: #555 !important;">Status</th>
                                    <th style="color: #555 !important;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $app)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold" style="color: #333 !important;">{{ $app->first_name }} {{ $app->last_name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $app->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td style="color: #444 !important;">{{ optional($app->recruitment)->title ?? 'N/A' }}</td>
                                        <td>
                                            @if($app->status == 'new')
                                                <span class="badge badge-info">New</span>
                                            @elseif($app->status == 'reviewed')
                                                <span class="badge badge-primary">Reviewed</span>
                                            @elseif($app->status == 'shortlisted')
                                                <span class="badge badge-success">Shortlisted</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.applications.download', $app->id) }}" class="btn btn-xs btn-outline-info" target="_blank" title="View CV">
                                                <i class="fa fa-file-pdf-o"></i> CV
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No applications received yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
