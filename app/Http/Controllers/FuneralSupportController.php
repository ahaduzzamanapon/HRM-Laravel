<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FuneralSupport;
use App\Models\User;
use Flash;

class FuneralSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funeralSupports = FuneralSupport::with('employee')->paginate(10);
        return view('funeral_supports.index', compact('funeralSupports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('funeral_supports.create', compact('users'));
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
        FuneralSupport::create($input);
        Flash::success('Funeral Support saved successfully.');
        return redirect(route('funeralSupports.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $funeralSupport = FuneralSupport::with('employee')->find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }
        return view('funeral_supports.show')->with('funeralSupport', $funeralSupport);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $funeralSupport = FuneralSupport::find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }
        $users = User::all();
        return view('funeral_supports.edit', compact('funeralSupport', 'users'));
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
        $funeralSupport = FuneralSupport::find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }
        $funeralSupport->fill($request->all());
        $funeralSupport->save();
        Flash::success('Funeral Support updated successfully.');
        return redirect(route('funeralSupports.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $funeralSupport = FuneralSupport::find($id);
        if (empty($funeralSupport)) {
            Flash::error('Funeral Support not found');
            return redirect(route('funeralSupports.index'));
        }
        $funeralSupport->delete();
        Flash::success('Funeral Support deleted successfully.');
        return redirect(route('funeralSupports.index'));
    }
}
