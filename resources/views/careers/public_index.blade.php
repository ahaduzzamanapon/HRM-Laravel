@extends('layouts.public')

@section('content')
<style>
    .career-hero {
        background: linear-gradient(135deg, #003366 0%, #001a33 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        border-bottom: 5px solid #d4af37; /* Gold accent */
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .career-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .career-hero .lead {
        font-size: 1.15rem;
        font-weight: 300;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .career-card {
        border: 1px solid #eaeaea;
        border-radius: 0;
        border-top: 4px solid transparent;
        transition: all 0.3s ease;
        background: #ffffff;
    }
    .career-card:hover {
        border-top: 4px solid #003366;
        box-shadow: 0 10px 25px rgba(0, 51, 102, 0.1);
        transform: translateY(-5px);
    }
    .job-title {
        color: #003366;
        font-weight: 700;
    }
    .badge-corporate {
        background-color: #f0f4f8;
        color: #003366;
        border: 1px solid #d1dbe5;
        border-radius: 0;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .btn-corporate {
        background-color: transparent;
        color: #003366;
        border: 2px solid #003366;
        border-radius: 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
    }
    .btn-corporate:hover {
        background-color: #003366;
        color: #ffffff;
    }
    .section-title {
        color: #003366;
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: #d4af37; /* Gold accent */
    }
    
    /* Make filter sticky only on desktop to save mobile screen space */
    @media (min-width: 992px) {
        .desktop-sticky {
            position: sticky;
            top: 80px;
            z-index: 1020;
        }
    }
    
    /* Mobile specific adjustments */
    @media (max-width: 767px) {
        .career-hero {
            padding: 40px 0;
        }
        .career-hero h1 {
            font-size: 2rem;
        }
        .career-hero .lead {
            font-size: 1rem;
        }
        .input-group-lg > .form-control {
            font-size: 1rem;
        }
    }
</style>

@php
    $siteSetting = \App\Models\SiteSetting::first();
    $careerTitle = $siteSetting->career_title ?? 'Discover Your Future';
    $careerSubtitle = $siteSetting->career_subtitle ?? 'Build a rewarding career with an institution committed to excellence, integrity, and growth.';
@endphp

<div class="career-hero">
    <div class="container">
        <h1>{{ $careerTitle }}</h1>
        <p class="lead">{{ $careerSubtitle }}</p>
    </div>
</div>

<div class="container py-4 mt-2">
    <div class="row mb-3">
        <div class="col-12 text-center">
            <h2 class="section-title font-weight-bold">Current Opportunities</h2>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="row mb-4 justify-content-center desktop-sticky">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: #ffffff; border-top: 3px solid #d4af37;">
                <div class="card-body p-3">
                    <form id="search-form">
                        @csrf
                        <div class="form-row align-items-center mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0 text-muted"><i class="fa fa-search"></i></span>
                                    </div>
                                    <input type="text" name="search" id="search-input" class="form-control border-left-0 pl-0" placeholder="Search by job title, role, or keywords..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0 text-muted"><i class="fa fa-briefcase"></i></span>
                                    </div>
                                    <select name="type" id="type-select" class="form-control border-left-0 pl-0">
                                        <option value="">All Job Types</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Freelance">Freelance</option>
                                        <option value="Internship">Internship</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0 text-muted"><i class="fa fa-map-marker-alt"></i></span>
                                    </div>
                                    <select name="location_type" id="location-select" class="form-control border-left-0 pl-0">
                                        <option value="">All Locations</option>
                                        <option value="Remote">Remote Only</option>
                                        <option value="On-site">On-site Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-right-0 text-muted"><i class="fa fa-sort"></i></span>
                                    </div>
                                    <select name="sort" id="sort-select" class="form-control border-left-0 pl-0">
                                        <option value="recent">Most Recent</option>
                                        <option value="old">Oldest First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="job-list-container">
        @include('careers.partials.job_list')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type-select');
        const locationSelect = document.getElementById('location-select');
        const searchInput = document.getElementById('search-input');
        const sortSelect = document.getElementById('sort-select');
        const jobListContainer = document.getElementById('job-list-container');
        const form = document.getElementById('search-form');
        
        let debounceTimer;

        function fetchJobs(page = 1) {
            const formData = new FormData(form);
            formData.append('page', page);
            
            //fetch("{{ route('careers.search') }}", {
            const searchUrl = "{{ route('careers.search') }}".replace("http://", "https://");
            console.log('Fetching jobs from:', searchUrl, 'with data:', Object.fromEntries(formData.entries()));
            fetch(searchUrl, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.text())
            .then(html => {
                jobListContainer.innerHTML = html;
            })
            .catch(error => console.error('Error fetching jobs:', error));
        }

        searchInput.addEventListener('keyup', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchJobs(1), 300); // 300ms debounce
        });

        sortSelect.addEventListener('change', () => fetchJobs(1));
        typeSelect.addEventListener('change', () => fetchJobs(1));
        locationSelect.addEventListener('change', () => fetchJobs(1));
        
        // Prevent default submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchJobs(1);
        });

        // Pagination click handler
        jobListContainer.addEventListener('click', function(e) {
            const pageLink = e.target.closest('.pagination a');
            if (pageLink) {
                e.preventDefault();
                const url = new URL(pageLink.href, window.location.origin);
                const page = url.searchParams.get('page');
                if (page) {
                    fetchJobs(page);
                    // Scroll to top of list smoothly
                    document.querySelector('.section-title').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
</script>
@endsection
