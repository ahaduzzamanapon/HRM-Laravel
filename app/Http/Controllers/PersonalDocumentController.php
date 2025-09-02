<?php

namespace App\Http\Controllers;

use App\Models\PersonalDocument;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class PersonalDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personalDocuments = PersonalDocument::paginate(10);
        return view('personal_documents.index')->with('personalDocuments', $personalDocuments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('personal_documents.create')->with('users', $users);
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

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $folder = 'documents/personal';
            $customName = 'personal-document-'.time();
            $input['document_file'] = uploadFile($file, $folder, $customName);
        }

        PersonalDocument::create($input);

        Flash::success('Personal Document saved successfully.');
        return redirect(route('personalDocuments.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personalDocument = PersonalDocument::find($id);

        if (empty($personalDocument)) {
            Flash::error('Personal Document not found');
            return redirect(route('personalDocuments.index'));
        }

        return view('personal_documents.show')->with('personalDocument', $personalDocument);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $personalDocument = PersonalDocument::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown

        if (empty($personalDocument)) {
            Flash::error('Personal Document not found');
            return redirect(route('personalDocuments.index'));
        }

        return view('personal_documents.edit')->with(['personalDocument' => $personalDocument, 'users' => $users]);
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
        $personalDocument = PersonalDocument::find($id);

        if (empty($personalDocument)) {
            Flash::error('Personal Document not found');
            return redirect(route('personalDocuments.index'));
        }

        $input = $request->all();

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $folder = 'documents/personal';
            $customName = 'personal-document-'.time();
            $input['document_file'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document_file']); // Don't update document if not provided
        }

        $personalDocument->update($input);

        Flash::success('Personal Document updated successfully.');
        return redirect(route('personalDocuments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $personalDocument = PersonalDocument::find($id);

        if (empty($personalDocument)) {
            Flash::error('Personal Document not found');
            return redirect(route('personalDocuments.index'));
        }

        // Delete associated document if exists
        if ($personalDocument->document_file && file_exists(public_path($personalDocument->document_file))) {
            unlink(public_path($personalDocument->document_file));
        }

        $personalDocument->delete();

        Flash::success('Personal Document deleted successfully.');
        return redirect(route('personalDocuments.index'));
    }
}
