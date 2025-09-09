<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Innovation;
use App\Models\User;
use Flash;

class InnovationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $innovations = Innovation::with(['employee', 'verifier'])->paginate(10);
        return view('innovations.index', compact('innovations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('innovations.create', compact('users'));
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
            $folder = 'documents/innovations';
            $customName = 'documents-' . time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            $input['document'] = null;
        }
        Innovation::create($input);
        Flash::success('Innovation saved successfully.');
        return redirect(route('innovations.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $innovation = Innovation::with(['employee', 'verifier'])->find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        return view('innovations.show')->with('innovation', $innovation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $innovation = Innovation::find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        $users = User::all();
        return view('innovations.edit', compact('innovation', 'users'));
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
        $innovation = Innovation::find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        $input = $request->all();
        if ($request->hasFile('document')) {
            $input['document'] = uploadFile($request->file('document'), 'documents');
        }
        $innovation->fill($input);
        $innovation->save();
        Flash::success('Innovation updated successfully.');
        return redirect(route('innovations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $innovation = Innovation::find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        $innovation->delete();
        Flash::success('Innovation deleted successfully.');
        return redirect(route('innovations.index'));
    }
}
