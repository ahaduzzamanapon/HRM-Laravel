<?php

namespace App\Http\Controllers;

use App\Models\ChildAllowance;
use App\Models\User;
use Illuminate\Http\Request;

class ChildAllowanceController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $input['updated_by'] = auth()->id();

        if (isset($input['start_month'])) {
            $input['start_month'] = $input['start_month'] . '-01';
        }

        if (isset($input['end_month'])) {
            $input['end_month'] = $input['end_month'] . '-01';
        }

        ChildAllowance::create($input);

        return response()->json(['message' => 'Child Allowance saved successfully.'], 201);
    }

    public function edit($id)
    {
        $childAllowance = ChildAllowance::find($id);

        if (empty($childAllowance)) {
            return response()->json(['error' => 'Child Allowance not found'], 404);
        }

        return response()->json(['childAllowance' => $childAllowance]);
    }

    public function update(Request $request, $id)
    {
        $childAllowance = ChildAllowance::find($id);

        if (empty($childAllowance)) {
            return response()->json(['error' => 'Child Allowance not found'], 404);
        }

        $input = $request->all();
        $input['updated_by'] = auth()->id();

        if (isset($input['start_month'])) {
            $input['start_month'] = $input['start_month'] . '-01';
        }

        if (isset($input['end_month'])) {
            $input['end_month'] = $input['end_month'] . '-01';
        }

        $childAllowance->update($input);

        return response()->json(['success' => true, 'message' => 'Child Allowance updated successfully.'], 200);
    }

    public function destroy($id)
    {
        $childAllowance = ChildAllowance::find($id);

        if (empty($childAllowance)) {
            return response()->json(['error' => 'Child Allowance not found'], 404);
        }

        $childAllowance->delete();

        return response()->json(['success' => true, 'message' => 'Child Allowance deleted successfully.'], 200);
    }

    public function list($user_id)
    {
        $childAllowances = ChildAllowance::where('user_id', $user_id)->get();
        return response()->json(['childAllowances' => $childAllowances], 200);
    }
}
