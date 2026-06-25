<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JobPost;

class CareerController extends Controller
{
    public function index()
    {
        // Utilizes the scopeActive() defined in the Model
        $jobs = JobPost::active()->latest()->get();
        
        return view('careers.index', compact('jobs'));
    }

    public function show(JobPost $jobPost)
    {
        // Prevent users from viewing draft/closed jobs
        abort_if($jobPost->status !== 'published', 404);

        return view('careers.show', compact('jobPost'));
    }
}
