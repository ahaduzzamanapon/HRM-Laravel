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
    public function index(Request $request)
    {
        $query = Rewarding::with('user');
        applyUserBranchScope($query, 'user');
        $rewardings = $query->paginate(10);

        return view('rewardings.index')->with('rewardings', $rewardings);
    }

    public function create()
    {
        $empQuery = User::where('group_id', '!=', 1)->where('status', 'active');
        applyBranchScope($empQuery, 'branch_id');
        $users = $empQuery->pluck('name', 'id');

        return view('rewardings.create', compact('users'));
    }

    public function store(CreateRewardingRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('document')) {
            $input['document'] = uploadFile($request->file('document'), 'documents/rewardings', 'rewarding-' . time());
        }

        Rewarding::create($input);
        Flash::success('Rewarding saved successfully.');
        return redirect(route('rewardings.index'));
    }

    public function show($id)
    {
        $rewarding = Rewarding::with('user')->find($id);
        if (empty($rewarding)) {
            Flash::error('Rewarding not found');
            return redirect(route('rewardings.index'));
        }
        enforceBranchOwnership($rewarding);
        return view('rewardings.show')->with('rewarding', $rewarding);
    }

    public function edit($id)
    {
        $rewarding = Rewarding::with('user')->find($id);
        if (empty($rewarding)) {
            Flash::error('Rewarding not found');
            return redirect(route('rewardings.index'));
        }
        enforceBranchOwnership($rewarding);

        $empQuery = User::where('group_id', '!=', 1)->where('status', 'active');
        applyBranchScope($empQuery, 'branch_id');
        $users = $empQuery->pluck('name', 'id');

        return view('rewardings.edit', compact('rewarding', 'users'));
    }

    public function update($id, UpdateRewardingRequest $request)
    {
        $rewarding = Rewarding::with('user')->find($id);
        if (empty($rewarding)) {
            Flash::error('Rewarding not found');
            return redirect(route('rewardings.index'));
        }
        enforceBranchOwnership($rewarding);

        $input = $request->all();
        if ($request->hasFile('document')) {
            $input['document'] = uploadFile($request->file('document'), 'documents/rewardings', 'rewarding-' . time());
        }

        $rewarding->fill($input);
        $rewarding->save();
        Flash::success('Rewarding updated successfully.');
        return redirect(route('rewardings.index'));
    }

    public function destroy($id)
    {
        $rewarding = Rewarding::with('user')->find($id);
        if (empty($rewarding)) {
            Flash::error('Rewarding not found');
            return redirect(route('rewardings.index'));
        }
        enforceBranchOwnership($rewarding);

        $rewarding->delete();
        Flash::success('Rewarding deleted successfully.');
        return redirect(route('rewardings.index'));
    }
}
