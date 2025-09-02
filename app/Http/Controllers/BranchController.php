<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Branch;
use Illuminate\Http\Request;
use Flash;
use Response;

class BranchController extends AppBaseController
{
    /**
     * Display a listing of the Branch.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Branch $branches */
        $branches = Branch::paginate(10);

        return view('branches.index')
            ->with('branches', $branches);
    }

    /**
     * Show the form for creating a new Branch.
     *
     * @return Response
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created Branch in storage.
     *
     * @param CreateBranchRequest $request
     *
     * @return Response
     */
    public function store(CreateBranchRequest $request)
    {
        $input = $request->all();

        /** @var Branch $branch */
        $branch = Branch::create($input);

        Flash::success('Branch saved successfully.');

        return redirect(route('branches.index'));
    }

    /**
     * Display the specified Branch.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Branch $branch */
        $branch = Branch::find($id);

        if (empty($branch)) {
            Flash::error('Branch not found');

            return redirect(route('branches.index'));
        }

        return view('branches.show')->with('branch', $branch);
    }

    /**
     * Show the form for editing the specified Branch.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Branch $branch */
        $branch = Branch::find($id);

        if (empty($branch)) {
            Flash::error('Branch not found');

            return redirect(route('branches.index'));
        }

        return view('branches.edit')->with('branch', $branch);
    }

    /**
     * Update the specified Branch in storage.
     *
     * @param int $id
     * @param UpdateBranchRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBranchRequest $request)
    {
        /** @var Branch $branch */
        $branch = Branch::find($id);

        if (empty($branch)) {
            Flash::error('Branch not found');

            return redirect(route('branches.index'));
        }

        $branch->fill($request->all());
        $branch->save();

        Flash::success('Branch updated successfully.');

        return redirect(route('branches.index'));
    }

    /**
     * Remove the specified Branch from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Branch $branch */
        $branch = Branch::find($id);

        if (empty($branch)) {
            Flash::error('Branch not found');

            return redirect(route('branches.index'));
        }

        $branch->delete();

        Flash::success('Branch deleted successfully.');

        return redirect(route('branches.index'));
    }
}
