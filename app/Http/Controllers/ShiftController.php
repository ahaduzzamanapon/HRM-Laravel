<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Branch; // Import Branch model
use Illuminate\Http\Request;
use Flash;
use Response;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::paginate(10);
        return view('shifts.index')->with('shifts', $shifts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::pluck('branch_name', 'id'); // Get branches for dropdown
        return view('shifts.create')->with('branches', $branches);
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

        $shift = Shift::create([
            'shift_name' => $input['shift_name'],
            'branch_id' => $input['branch_id'],
        ]);

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($daysOfWeek as $day) {
            $shift->shiftDetails()->create([
                'day_of_week' => $day,
                'in_time' => $input[$day . '_in_time'] ?? null,
                'out_time' => $input[$day . '_out_time'] ?? null,
                'late_start_time' => $input[$day . '_late_start_time'] ?? null,
                'lunch_start_time' => $input[$day . '_lunch_start_time'] ?? null,
                'lunch_end_time' => $input[$day . '_lunch_end_time'] ?? null,
                'is_weekend' => isset($input[$day . '_is_weekend']),
            ]);
        }

        Flash::success('Shift saved successfully.');
        return redirect(route('shifts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shift = Shift::find($id);

        if (empty($shift)) {
            Flash::error('Shift not found');
            return redirect(route('shifts.index'));
        }

        return view('shifts.show')->with('shift', $shift);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shift = Shift::find($id);
        $branches = Branch::pluck('branch_name', 'id'); // Get branches for dropdown

        if (empty($shift)) {
            Flash::error('Shift not found');
            return redirect(route('shifts.index'));
        }

        return view('shifts.edit')->with(['shift' => $shift, 'branches' => $branches]);
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
        $shift = Shift::find($id);

        if (empty($shift)) {
            Flash::error('Shift not found');
            return redirect(route('shifts.index'));
        }

        $input = $request->all();

        $shift->update([
            'shift_name' => $input['shift_name'],
            'branch_id' => $input['branch_id'],
        ]);

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($daysOfWeek as $day) {
            $shiftDetail = $shift->shiftDetails()->where('day_of_week', $day)->first();

            if ($shiftDetail) {
                $shiftDetail->update([
                    'in_time' => $input[$day . '_in_time'] ?? null,
                    'out_time' => $input[$day . '_out_time'] ?? null,
                    'late_start_time' => $input[$day . '_late_start_time'] ?? null,
                    'lunch_start_time' => $input[$day . '_lunch_start_time'] ?? null,
                    'lunch_end_time' => $input[$day . '_lunch_end_time'] ?? null,
                    'is_weekend' => isset($input[$day . '_is_weekend']),
                ]);
            } else {
                $shift->shiftDetails()->create([
                    'day_of_week' => $day,
                    'in_time' => $input[$day . '_in_time'] ?? null,
                    'out_time' => $input[$day . '_out_time'] ?? null,
                    'late_start_time' => $input[$day . '_late_start_time'] ?? null,
                    'lunch_start_time' => $input[$day . '_lunch_start_time'] ?? null,
                    'lunch_end_time' => $input[$day . '_lunch_end_time'] ?? null,
                    'is_weekend' => isset($input[$day . '_is_weekend']),
                ]);
            }
        }

        Flash::success('Shift updated successfully.');
        return redirect(route('shifts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shift = Shift::find($id);

        if (empty($shift)) {
            Flash::error('Shift not found');
            return redirect(route('shifts.index'));
        }

        $shift->delete();

        Flash::success('Shift deleted successfully.');
        return redirect(route('shifts.index'));
    }
}
