<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;

class RecruitmentDashboardController extends Controller
{
    public function index()
    {
        // Job Stats
        $jobStats = [
            'total' => Recruitment::count(),
            'published' => Recruitment::where('status', 'published')->count(),
            'draft' => Recruitment::where('status', 'draft')->count(),
            'closed' => Recruitment::where('status', 'closed')->count(),
        ];

        // Application Stats
        $appStats = [
            'total' => JobApplication::count(),
            'new' => JobApplication::where('status', 'new')->count(),
            'reviewed' => JobApplication::where('status', 'reviewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];

        // Job posts with applications count
        $jobsWithAppCounts = Recruitment::select('recruitments.id', 'recruitments.title', 'recruitments.status', 'recruitments.deadline')
            ->selectRaw('count(job_applications.id) as applications_count')
            ->leftJoin('job_applications', 'recruitments.id', '=', 'job_applications.recruitment_id')
            ->groupBy('recruitments.id', 'recruitments.title', 'recruitments.status', 'recruitments.deadline')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        // Recent Applications
        $recentApplications = JobApplication::with('recruitment')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.recruitment.dashboard', compact('jobStats', 'appStats', 'jobsWithAppCounts', 'recentApplications'));
    }
}
