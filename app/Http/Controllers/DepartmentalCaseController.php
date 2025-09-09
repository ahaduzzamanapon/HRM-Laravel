<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DepartmentalCase;
use App\Models\User;
use App\Models\Penalty;
use Flash;

class DepartmentalCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departmentalCases = DepartmentalCase::with(['employee', 'penalty'])->paginate(10);
        return view('departmental_cases.index', compact('departmentalCases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $penalties = Penalty::all();
        return view('departmental_cases.create', compact('users', 'penalties'));
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
        DepartmentalCase::create($input);
        Flash::success('Departmental Case saved successfully.');
        return redirect(route('departmentalCases.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $departmentalCase = DepartmentalCase::with(['employee', 'penalty'])->find($id);
        if (empty($departmentalCase)) {
            Flash::error('Departmental Case not found');
            return redirect(route('departmentalCases.index'));
        }
        return view('departmental_cases.show')->with('departmentalCase', $departmentalCase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departmentalCase = DepartmentalCase::find($id);
        if (empty($departmentalCase)) {
            Flash::error('Departmental Case not found');
            return redirect(route('departmentalCases.index'));
        }
        $users = User::all();
        $penalties = Penalty::all();
        return view('departmental_cases.edit', compact('departmentalCase', 'users', 'penalties'));
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
        $departmentalCase = DepartmentalCase::find($id);
        if (empty($departmentalCase)) {
            Flash::error('Departmental Case not found');
            return redirect(route('departmentalCases.index'));
        }
        $departmentalCase->fill($request->all());
        $departmentalCase->save();
        Flash::success('Departmental Case updated successfully.');
        return redirect(route('departmentalCases.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departmentalCase = DepartmentalCase::find($id);
        if (empty($departmentalCase)) {
            Flash::error('Departmental Case not found');
            return redirect(route('departmentalCases.index'));
        }
        $departmentalCase->delete();
        Flash::success('Departmental Case deleted successfully.');
        return redirect(route('departmentalCases.index'));
    }
}
