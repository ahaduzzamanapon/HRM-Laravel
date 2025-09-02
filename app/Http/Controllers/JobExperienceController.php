<?php

namespace App\Http\Controllers;

use App\Models\JobExperience;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class JobExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobExperiences = JobExperience::paginate(10);
        return view('job_experiences.index')->with('jobExperiences', $jobExperiences);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('job_experiences.create')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        JobExperience::create($input);

        Flash::success('Job Experience saved successfully.');
        return redirect(route('jobExperiences.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobExperience = JobExperience::find($id);

        if (empty($jobExperience)) {
            Flash::error('Job Experience not found');
            return redirect(route('jobExperiences.index'));
        }

        return view('job_experiences.show')->with('jobExperience', $jobExperience);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jobExperience = JobExperience::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown

        if (empty($jobExperience)) {
            Flash::error('Job Experience not found');
            return redirect(route('jobExperiences.index'));
        }

        return view('job_experiences.edit')->with(['jobExperience' => $jobExperience, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jobExperience = JobExperience::find($id);

        if (empty($jobExperience)) {
            Flash::error('Job Experience not found');
            return redirect(route('jobExperiences.index'));
        }

        $jobExperience->update($request->all());

        Flash::success('Job Experience updated successfully.');
        return redirect(route('jobExperiences.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobExperience = JobExperience::find($id);

        if (empty($jobExperience)) {
            Flash::error('Job Experience not found');
            return redirect(route('jobExperiences.index'));
        }

        $jobExperience->delete();

        Flash::success('Job Experience deleted successfully.');
        return redirect(route('jobExperiences.index'));
    }
}
