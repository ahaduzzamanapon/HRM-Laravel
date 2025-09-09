<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MedicalSupport;
use App\Models\User;
use Flash;

class MedicalSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicalSupports = MedicalSupport::with('employee')->paginate(10);
        return view('medical_supports.index', compact('medicalSupports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('medical_supports.create', compact('users'));
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
        MedicalSupport::create($input);
        Flash::success('Medical Support saved successfully.');
        return redirect(route('medicalSupports.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medicalSupport = MedicalSupport::with('employee')->find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }
        return view('medical_supports.show')->with('medicalSupport', $medicalSupport);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medicalSupport = MedicalSupport::find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }
        $users = User::all();
        return view('medical_supports.edit', compact('medicalSupport', 'users'));
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
        $medicalSupport = MedicalSupport::find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }
        $medicalSupport->fill($request->all());
        $medicalSupport->save();
        Flash::success('Medical Support updated successfully.');
        return redirect(route('medicalSupports.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $medicalSupport = MedicalSupport::find($id);
        if (empty($medicalSupport)) {
            Flash::error('Medical Support not found');
            return redirect(route('medicalSupports.index'));
        }
        $medicalSupport->delete();
        Flash::success('Medical Support deleted successfully.');
        return redirect(route('medicalSupports.index'));
    }
}
