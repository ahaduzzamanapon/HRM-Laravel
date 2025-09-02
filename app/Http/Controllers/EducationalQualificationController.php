<?php

namespace App\Http\Controllers;

use App\Models\EducationalQualification;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class EducationalQualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $educationalQualifications = EducationalQualification::paginate(10);
        return view('educational_qualifications.index')->with('educationalQualifications', $educationalQualifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('educational_qualifications.create')->with('users', $users);
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

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/educational_qualifications';
            $customName = 'educational-qualification-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        EducationalQualification::create($input);

        Flash::success('Educational Qualification saved successfully.');
        return redirect(route('educationalQualifications.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $educationalQualification = EducationalQualification::find($id);

        if (empty($educationalQualification)) {
            Flash::error('Educational Qualification not found');
            return redirect(route('educationalQualifications.index'));
        }

        return view('educational_qualifications.show')->with('educationalQualification', $educationalQualification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $educationalQualification = EducationalQualification::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown

        if (empty($educationalQualification)) {
            Flash::error('Educational Qualification not found');
            return redirect(route('educationalQualifications.index'));
        }

        return view('educational_qualifications.edit')->with(['educationalQualification' => $educationalQualification, 'users' => $users]);
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
        $educationalQualification = EducationalQualification::find($id);

        if (empty($educationalQualification)) {
            Flash::error('Educational Qualification not found');
            return redirect(route('educationalQualifications.index'));
        }

        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/educational_qualifications';
            $customName = 'educational-qualification-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']); // Don't update document if not provided
        }

        $educationalQualification->update($input);

        Flash::success('Educational Qualification updated successfully.');
        return redirect(route('educationalQualifications.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educationalQualification = EducationalQualification::find($id);

        if (empty($educationalQualification)) {
            Flash::error('Educational Qualification not found');
            return redirect(route('educationalQualifications.index'));
        }

        // Delete associated document if exists
        if ($educationalQualification->document && file_exists(public_path($educationalQualification->document))) {
            unlink(public_path($educationalQualification->document));
        }

        $educationalQualification->delete();

        Flash::success('Educational Qualification deleted successfully.');
        return redirect(route('educationalQualifications.index'));
    }
}
