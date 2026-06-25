<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JobApplication;
use App\Http\Requests\StoreJobApplicationRequest;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function store(StoreJobApplicationRequest $request)
    {
        $validated = $request->validated();

        // 1. Store the resume securely in 'storage/app/resumes'
        // We do NOT use the 'public' disk here to protect applicant privacy.
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes');
            $validated['resume_path'] = $path;
        }

        // 2. Save Application
        JobApplication::create($validated);

        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    }
}
