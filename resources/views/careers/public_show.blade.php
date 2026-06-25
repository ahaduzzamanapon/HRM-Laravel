@extends('layouts.public')

@section('content')
<style>
    .job-header {
        background: #001a33;
        padding: 50px 0;
        border-bottom: 5px solid #d4af37; /* Gold accent */
        color: #ffffff;
    }
    .job-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: #ffffff;
        font-size: 2.5rem;
    }
    .apply-btn {
        background-color: #d4af37;
        color: #001a33;
        border: none;
        padding: 12px 35px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 0;
        transition: all 0.3s;
    }
    .apply-btn:hover {
        background-color: #e6c24b;
        color: #001a33;
        transform: translateY(-2px);
    }
    .job-meta-box {
        background: #ffffff;
        border-radius: 0;
        padding: 30px;
        border-top: 4px solid #003366;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .job-meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .job-meta-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0, 51, 102, 0.1);
        color: #003366;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 15px;
    }
    .section-heading {
        font-family: 'Playfair Display', serif;
        color: #003366;
        font-weight: 700;
        border-bottom: 2px solid #eaeaea;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .job-description-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #4a5568;
    }
    .apply-section-footer {
        background: #f8f9fa;
        border: 1px solid #eaeaea;
        border-left: 4px solid #d4af37;
    }
    
    @media (min-width: 992px) {
        .desktop-sticky {
            position: sticky;
            top: 100px;
            z-index: 1000;
        }
    }
    
    @media (max-width: 767px) {
        .job-header {
            padding: 30px 0;
        }
        .job-title {
            font-size: 1.8rem;
        }
        .job-description-content {
            font-size: 1rem;
        }
        .apply-section-footer {
            padding: 1.5rem !important;
        }
    }
    
    .fixed-top-right-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 450px;
        border-left: 5px solid #28a745;
        animation: slideInRight 0.5s ease-out forwards, fadeOut 0.5s ease-in 4.5s forwards;
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; visibility: hidden; }
    }
</style>

<div class="job-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-left mb-4 mb-md-0">
                <a href="{{ route('careers.public') }}" class="btn btn-sm btn-outline-light rounded-0 mb-3" style="text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fa fa-arrow-left"></i> All Opportunities
                </a>
                <h1 class="job-title mb-2">{{ $job->title }}</h1>
                <p class="lead" style="color: #d4af37;"><i class="fa fa-map-marker-alt"></i> {{ $job->location ?? 'Remote' }}</p>
            </div>
            <div class="col-md-4 text-center text-md-right">
                <a href="#apply-section" class="btn apply-btn btn-lg">
                    Apply Now <i class="fa fa-chevron-right ml-2" style="font-size: 0.8em;"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 mt-3">
    <div class="row">
        <div class="col-lg-8 pr-lg-5 mb-5 mb-lg-0">
            <h3 class="section-heading">Position Overview</h3>
            <div class="job-description-content">
                {!! $job->description !!}
            </div>

            <div id="apply-section" class="apply-section-footer p-4 p-md-5 mt-5">
                <h3 class="font-weight-bold mb-3" style="color: #003366; font-family: 'Playfair Display', serif;">Ready to Advance Your Career?</h3>
                <p class="text-muted mb-4">Please complete the form below and upload your resume to apply for this position.</p>
                
                @if(session('success'))
                    <div class="alert alert-success fixed-top-right-toast shadow-lg alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-check-circle mr-2"></i> Success!</strong><br>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <form action="{{ route('careers.apply', $job->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Honeypot Field to prevent bot spam (Hidden from humans, bots will fill it) -->
                    <div style="display: none;">
                        <label for="website_url">Leave this field empty if you are human:</label>
                        <input type="text" name="website_url" id="website_url" value="" autocomplete="off">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-0">
                        </div>
                        <div class="col-12 form-group mb-3">
                            <label class="font-weight-bold">Cover Letter</label>
                            <textarea name="cover_letter" rows="4" class="form-control rounded-0"></textarea>
                        </div>
                        <div class="col-12 form-group mb-4">
                            <label class="font-weight-bold">Resume (PDF/Doc) <span class="text-danger">*</span></label>
                            <input type="file" name="resume" class="form-control-file" accept=".pdf,.doc,.docx" required>
                        </div>
                        <div class="col-12 text-right">
                            <button type="submit" class="btn apply-btn">
                                <i class="fa fa-paper-plane mr-2"></i> Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="job-meta-box desktop-sticky">
                <h4 class="font-weight-bold mb-4 border-bottom pb-3" style="color: #003366; font-family: 'Playfair Display', serif;">Position Details</h4>
                
                <div class="job-meta-item">
                    <div class="job-meta-icon"><i class="fa fa-calendar-alt"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">Date Posted</div>
                        <div class="font-weight-bold text-dark">{{ $job->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                <div class="job-meta-item">
                    <div class="job-meta-icon"><i class="fa fa-briefcase"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">Employment Type</div>
                        <div class="font-weight-bold text-dark">{{ $job->employment_type }}</div>
                    </div>
                </div>

                @if($job->experience_level)
                <div class="job-meta-item">
                    <div class="job-meta-icon"><i class="fa fa-user-tie"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">Experience Level</div>
                        <div class="font-weight-bold text-dark">{{ $job->experience_level }}</div>
                    </div>
                </div>
                @endif

                @if($job->salary_range_start || $job->salary_range_end)
                <div class="job-meta-item">
                    <div class="job-meta-icon"><i class="fa fa-coins"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">Salary Range</div>
                        <div class="font-weight-bold text-dark">
                            @if($job->salary_range_start && $job->salary_range_end)
                                ৳{{ number_format($job->salary_range_start) }} - ৳{{ number_format($job->salary_range_end) }}
                            @elseif($job->salary_range_start)
                                From ৳{{ number_format($job->salary_range_start) }}
                            @else
                                Up to ৳{{ number_format($job->salary_range_end) }}
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($job->deadline)
                <div class="job-meta-item pt-3 mt-3 border-top">
                    <div class="job-meta-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;"><i class="fa fa-hourglass-half"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">Application Deadline</div>
                        <div class="font-weight-bold text-danger">{{ $job->deadline->format('M d, Y') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
