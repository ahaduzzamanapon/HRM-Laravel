<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHolydayRequest;
use App\Http\Requests\UpdateHolydayRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Holyday;
use Illuminate\Http\Request;
use Flash;
use Response;

class HolydayController extends AppBaseController
{
    /**
     * Display a listing of the Holyday.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Holyday $holydays */
        $holydays = Holyday::paginate(10);

        return view('holydays.index')
            ->with('holydays', $holydays);
    }

    /**
     * Show the form for creating a new Holyday.
     *
     * @return Response
     */
    public function create()
    {
        $branches = \App\Models\Branch::pluck('branch_name', 'id');
        return view('holydays.create')->with('branches', $branches);
    }

    /**
     * Store a newly created Holyday in storage.
     *
     * @param CreateHolydayRequest $request
     *
     * @return Response
     */
    public function store(CreateHolydayRequest $request)
    {
        $input = $request->all();

        /** @var Holyday $holyday */
        $holyday = Holyday::create($input);

        Flash::success('Holyday saved successfully.');

        return redirect(route('holydays.index'));
    }

    /**
     * Display the specified Holyday.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Holyday $holyday */
        $holyday = Holyday::find($id);

        if (empty($holyday)) {
            Flash::error('Holyday not found');

            return redirect(route('holydays.index'));
        }

        return view('holydays.show')->with('holyday', $holyday);
    }

    /**
     * Show the form for editing the specified Holyday.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Holyday $holyday */
        $holyday = Holyday::find($id);
        $branches = \App\Models\Branch::pluck('branch_name', 'id');

        if (empty($holyday)) {
            Flash::error('Holyday not found');

            return redirect(route('holydays.index'));
        }

        return view('holydays.edit')->with(['holyday' => $holyday, 'branches' => $branches]);
    }

    /**
     * Update the specified Holyday in storage.
     *
     * @param int $id
     * @param UpdateHolydayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHolydayRequest $request)
    {
        /** @var Holyday $holyday */
        $holyday = Holyday::find($id);

        if (empty($holyday)) {
            Flash::error('Holyday not found');

            return redirect(route('holydays.index'));
        }

        $holyday->fill($request->all());
        $holyday->save();

        Flash::success('Holyday updated successfully.');

        return redirect(route('holydays.index'));
    }

    /**
     * Remove the specified Holyday from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Holyday $holyday */
        $holyday = Holyday::find($id);

        if (empty($holyday)) {
            Flash::error('Holyday not found');

            return redirect(route('holydays.index'));
        }

        $holyday->delete();

        Flash::success('Holyday deleted successfully.');

        return redirect(route('holydays.index'));
    }
}
