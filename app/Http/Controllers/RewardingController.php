<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRewardingRequest;
use App\Http\Requests\UpdateRewardingRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Rewarding;
use App\Models\User;
use Illuminate\Http\Request;
use Flash;
use Response;

class RewardingController extends AppBaseController
{
    /**
     * Display a listing of the Rewarding.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Rewarding $rewardings */
        $rewardings = Rewarding::with('user')->paginate(10);

        return view('rewardings.index')
            ->with('rewardings', $rewardings);
    }

    /**
     * Show the form for creating a new Rewarding.
     *
     * @return Response
     */
    public function create()
    {
        $users = User::all();
        return view('rewardings.create', compact('users'));
    }

    /**
     * Store a newly created Rewarding in storage.
     *
     * @param CreateRewardingRequest $request
     *
     * @return Response
     */
    public function store(CreateRewardingRequest $request)
    {
        $input = $request->all();

          if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/rewardings';
            $customName = 'documents-' . time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            $input['document'] = 'no-file';
        }

        /** @var Rewarding $rewarding */
        $rewarding = Rewarding::create($input);

        Flash::success('Rewarding saved successfully.');

        return redirect(route('rewardings.index'));
    }

    /**
     * Display the specified Rewarding.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Rewarding $rewarding */
        $rewarding = Rewarding::find($id);

        if (empty($rewarding)) {
            Flash::error('Rewarding not found');

            return redirect(route('rewardings.index'));
        }

        return view('rewardings.show')->with('rewarding', $rewarding);
    }

    /**
     * Show the form for editing the specified Rewarding.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Rewarding $rewarding */
        $rewarding = Rewarding::find($id);

        if (empty($rewarding)) {
            Flash::error('Rewarding not found');

            return redirect(route('rewardings.index'));
        }
        $users = User::all();

        return view('rewardings.edit', compact('users'))->with('rewarding', $rewarding);
    }

    /**
     * Update the specified Rewarding in storage.
     *
     * @param int $id
     * @param UpdateRewardingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRewardingRequest $request)
    {
        /** @var Rewarding $rewarding */
        $rewarding = Rewarding::find($id);

        if (empty($rewarding)) {
            Flash::error('Rewarding not found');

            return redirect(route('rewardings.index'));
        }

        $rewarding->fill($request->all());
        $rewarding->save();

        Flash::success('Rewarding updated successfully.');

        return redirect(route('rewardings.index'));
    }

    /**
     * Remove the specified Rewarding from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Rewarding $rewarding */
        $rewarding = Rewarding::find($id);

        if (empty($rewarding)) {
            Flash::error('Rewarding not found');

            return redirect(route('rewardings.index'));
        }

        $rewarding->delete();

        Flash::success('Rewarding deleted successfully.');

        return redirect(route('rewardings.index'));
    }
}
