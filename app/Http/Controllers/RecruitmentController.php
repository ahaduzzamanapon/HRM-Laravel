<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recruitment;
use App\Models\Branch;

class RecruitmentController extends Controller
{
    public function index()
    {
        $recruitments = Recruitment::latest()->paginate(10);
        return view('admin.recruitments.index', compact('recruitments'));
    }

    public function create()
    {
        $branches = Branch::whereNotNull('Address')->pluck('Address', 'Address')->toArray();
        return view('admin.recruitments.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'salary_range_start' => 'nullable|numeric',
            'salary_range_end' => 'nullable|numeric',
            'deadline' => 'nullable|date',
            'status' => 'required|in:draft,published,closed',
        ]);

        Recruitment::create($validated);

        return redirect()->route('recruitments.index')->with('success', 'Job post created successfully.');
    }

    public function show($id)
    {
        $recruitment = Recruitment::findOrFail($id);
        return view('admin.recruitments.show', compact('recruitment'));
    }

    public function edit($id)
    {
        $recruitment = Recruitment::findOrFail($id);
        $branches = Branch::whereNotNull('Address')->pluck('Address', 'Address')->toArray();
        return view('admin.recruitments.edit', compact('recruitment', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'salary_range_start' => 'nullable|numeric',
            'salary_range_end' => 'nullable|numeric',
            'deadline' => 'nullable|date',
            'status' => 'required|in:draft,published,closed',
        ]);

        $recruitment = Recruitment::findOrFail($id);
        $recruitment->update($validated);

        return redirect()->route('recruitments.index')->with('success', 'Job post updated successfully.');
    }

    public function destroy($id)
    {
        $recruitment = Recruitment::findOrFail($id);
        $recruitment->delete();

        return redirect()->route('recruitments.index')->with('success', 'Job post deleted successfully.');
    }

    // Public career page
    public function careers(Request $request)
    {
        $query = Recruitment::where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->toDateString());
            });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Employment Type
        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        // Filter by Location Type (Remote/On-site)
        if ($request->filled('location_type')) {
            if ($request->location_type == 'Remote') {
                $query->where('location', 'like', '%Remote%');
            } elseif ($request->location_type == 'On-site') {
                $query->where(function($q) {
                    $q->where('location', 'not like', '%Remote%')
                      ->orWhereNull('location');
                });
            }
        }

        // Sorting functionality
        if ($request->filled('sort') && $request->sort == 'old') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(6);
            
        return view('careers.public_index', compact('jobs'));
    }

    // Live search for career page
    public function search(Request $request)
    {
        $query = Recruitment::where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->toDateString());
            });

        if ($request->filled('search')) {
            // strip_tags to sanitize search input (prevents XSS reflecting in DB queries or logs)
            $search = strip_tags($request->search);
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        if ($request->filled('location_type')) {
            if ($request->location_type == 'Remote') {
                $query->where('location', 'like', '%Remote%');
            } elseif ($request->location_type == 'On-site') {
                $query->where(function($q) {
                    $q->where('location', 'not like', '%Remote%')
                      ->orWhereNull('location');
                });
            }
        }

        if ($request->filled('sort') && $request->sort == 'old') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(6);

        return view('careers.partials.job_list', compact('jobs'))->render();
    }

    public function careerDetails($slug)
    {
        $job = Recruitment::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('careers.public_show', compact('job'));
    }

    public function apply(Request $request, $slug)
    {
        // Honeypot check: If the hidden 'website_url' field is filled, it's a bot.
        if ($request->filled('website_url')) {
            abort(403, 'Automated bot submission detected.');
        }

        $job = Recruitment::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB, prevents DoS
        ]);

        // Secure file upload: storing in 'local' disk instead of 'public'
        // This prevents Information Disclosure (IDOR) as resumes contain sensitive PII
        // and should never be publicly accessible via a URL.
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'local');
        }

        // Sanitize inputs to prevent Stored XSS
        \App\Models\JobApplication::create([
            'recruitment_id' => $job->id,
            'first_name' => strip_tags($request->first_name),
            'last_name' => strip_tags($request->last_name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'phone' => strip_tags($request->phone),
            'cover_letter' => strip_tags($request->cover_letter),
            'resume_path' => $resumePath,
            'status' => 'new'
        ]);

        return redirect()->back()->with('success', 'Your application has been submitted successfully! We will get in touch with you soon.');
    }
}
