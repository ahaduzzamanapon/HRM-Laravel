<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movements = Movement::with(['user', 'approver'])->paginate(10);
        return view('movements.index')->with('movements', $movements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $googleMapsApiKey = env('GOOGLE_MAPS_STATIC_API_KEY');
        return view('movements.create', compact('googleMapsApiKey'));
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
        $input['user_id'] = Auth::id();

        // Calculate TA amount based on distance and branch's TA rate
        $user = Auth::user();
        $branch = $user->branch; // Assuming user has a branch relationship

        $taRatePerKm = $branch->ta_rate_per_km ?? 0; // Get TA rate from branch settings
        $input['ta_amount'] = $input['distance'] * $taRatePerKm;
        $input['total_amount'] = $input['ta_amount'] + ($input['da_amount'] ?? 0);

        Movement::create($input);

        Flash::success('Movement saved successfully.');
        return redirect(route('movements.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movement = Movement::with(['user', 'approver'])->find($id);

        if (empty($movement)) {
            Flash::error('Movement not found');
            return redirect(route('movements.index'));
        }

        return view('movements.show')->with('movement', $movement);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movement = Movement::find($id);

        if (empty($movement)) {
            Flash::error('Movement not found');
            return redirect(route('movements.index'));
        }

        $googleMapsApiKey = env('GOOGLE_MAPS_STATIC_API_KEY');
        return view('movements.edit', compact('movement', 'googleMapsApiKey'));
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
        $movement = Movement::find($id);

        if (empty($movement)) {
            Flash::error('Movement not found');
            return redirect(route('movements.index'));
        }

        $input = $request->all();

        // Calculate TA amount based on distance and branch's TA rate
        $user = Auth::user();
        $branch = $user->branch; // Assuming user has a branch relationship

        $taRatePerKm = $branch->ta_rate_per_km ?? 0; // Get TA rate from branch settings
        $input['ta_amount'] = $input['distance'] * $taRatePerKm;
        $input['total_amount'] = $input['ta_amount'] + ($input['da_amount'] ?? 0);

        $movement->update($input);

        Flash::success('Movement updated successfully.');
        return redirect(route('movements.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movement = Movement::find($id);

        if (empty($movement)) {
            Flash::error('Movement not found');
            return redirect(route('movements.index'));
        }

        $movement->delete();

        Flash::success('Movement deleted successfully.');
        return redirect(route('movements.index'));
    }
}
