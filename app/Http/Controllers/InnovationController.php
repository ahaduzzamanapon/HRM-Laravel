<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Innovation;
use App\Models\User;
use Flash;

class InnovationController extends Controller
{
    public function index()
    {
        $query = Innovation::with(['employee', 'verifier']);
        applyUserBranchScope($query, 'employee');
        $innovations = $query->paginate(10);
        return view('innovations.index', compact('innovations'));
    }

    public function create()
    {
        $empQuery = User::where('status', 'active');
        applyBranchScope($empQuery, 'branch_id');
        $users = $empQuery->get();
        return view('innovations.create', compact('users'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->hasFile('document')) {
            $input['document'] = uploadFile($request->file('document'), 'documents/innovations', 'documents-' . time());
        } else {
            $input['document'] = null;
        }
        Innovation::create($input);
        Flash::success('Innovation saved successfully.');
        return redirect(route('innovations.index'));
    }

    public function show($id)
    {
        $innovation = Innovation::with(['employee', 'verifier'])->find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        enforceBranchOwnership($innovation->employee ?? null, 'branch_id');
        return view('innovations.show')->with('innovation', $innovation);
    }

    public function edit($id)
    {
        $innovation = Innovation::with('employee')->find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        enforceBranchOwnership($innovation->employee ?? null, 'branch_id');

        $empQuery = User::where('status', 'active');
        applyBranchScope($empQuery, 'branch_id');
        $users = $empQuery->get();
        return view('innovations.edit', compact('innovation', 'users'));
    }

    public function update(Request $request, $id)
    {
        $innovation = Innovation::with('employee')->find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        enforceBranchOwnership($innovation->employee ?? null, 'branch_id');

        $input = $request->all();
        if ($request->hasFile('document')) {
            $input['document'] = uploadFile($request->file('document'), 'documents/innovations', 'documents-' . time());
        }
        $innovation->fill($input);
        $innovation->save();
        Flash::success('Innovation updated successfully.');
        return redirect(route('innovations.index'));
    }

    public function destroy($id)
    {
        $innovation = Innovation::with('employee')->find($id);
        if (empty($innovation)) {
            Flash::error('Innovation not found');
            return redirect(route('innovations.index'));
        }
        enforceBranchOwnership($innovation->employee ?? null, 'branch_id');
        $innovation->delete();
        Flash::success('Innovation deleted successfully.');
        return redirect(route('innovations.index'));
    }
}
