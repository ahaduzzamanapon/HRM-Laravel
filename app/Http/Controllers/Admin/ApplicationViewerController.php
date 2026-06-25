<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Support\Facades\Storage;

class ApplicationViewerController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query with filters
        $applications = JobApplication::with('recruitment')
            ->when($request->filled('recruitment_id'), function ($query) use ($request) {
                $query->where('recruitment_id', $request->recruitment_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(15);

        $jobPosts = \App\Models\Recruitment::pluck('title', 'id');

        return view('admin.recruitment.applications.index', compact('applications', 'jobPosts'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewed,shortlisted,rejected'
        ]);

        $application->update($validated);

        return back()->with('success', 'Application status updated.');
    }

    public function downloadResume(JobApplication $application)
    {
        // 2. Safely serve the private file to authenticated admins
        if (!Storage::exists($application->resume_path)) {
            abort(404, 'Resume file not found.');
        }

        return Storage::response(
            $application->resume_path, 
            $application->first_name . '_' . $application->last_name . '_Resume.pdf',
            ['Content-Disposition' => 'inline; filename="' . $application->first_name . '_' . $application->last_name . '_Resume.pdf"']
        );
    }
}
