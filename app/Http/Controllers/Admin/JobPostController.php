<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobPost;
use App\Http\Requests\StoreJobPostRequest;
use App\Http\Requests\UpdateJobPostRequest;

class JobPostController extends Controller
{
    public function index()
    {
        $jobs = JobPost::latest()->paginate(10);
        return view('admin.recruitment.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.recruitment.jobs.create');
    }

    public function store(StoreJobPostRequest $request)
    {
        JobPost::create($request->validated());
        return redirect()->route('admin.jobs.index')->with('success', 'Job post created successfully.');
    }

    public function edit(JobPost $jobPost)
    {
        return view('admin.recruitment.jobs.edit', compact('jobPost'));
    }

    public function update(UpdateJobPostRequest $request, JobPost $jobPost)
    {
        $jobPost->update($request->validated());
        return redirect()->route('admin.jobs.index')->with('success', 'Job post updated successfully.');
    }

    public function destroy(JobPost $jobPost)
    {
        $jobPost->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job post deleted successfully.');
    }
}
