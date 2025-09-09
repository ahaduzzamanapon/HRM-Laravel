<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Penalty;
use Flash;

class PenaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penalties = Penalty::paginate(10);
        return view('penalties.index', compact('penalties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('penalties.create');
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
        Penalty::create($input);
        Flash::success('Penalty saved successfully.');
        return redirect(route('penalties.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penalty = Penalty::find($id);
        if (empty($penalty)) {
            Flash::error('Penalty not found');
            return redirect(route('penalties.index'));
        }
        return view('penalties.show')->with('penalty', $penalty);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penalty = Penalty::find($id);
        if (empty($penalty)) {
            Flash::error('Penalty not found');
            return redirect(route('penalties.index'));
        }
        return view('penalties.edit')->with('penalty', $penalty);
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
        $penalty = Penalty::find($id);
        if (empty($penalty)) {
            Flash::error('Penalty not found');
            return redirect(route('penalties.index'));
        }
        $penalty->fill($request->all());
        $penalty->save();
        Flash::success('Penalty updated successfully.');
        return redirect(route('penalties.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penalty = Penalty::find($id);
        if (empty($penalty)) {
            Flash::error('Penalty not found');
            return redirect(route('penalties.index'));
        }
        $penalty->delete();
        Flash::success('Penalty deleted successfully.');
        return redirect(route('penalties.index'));
    }
}
