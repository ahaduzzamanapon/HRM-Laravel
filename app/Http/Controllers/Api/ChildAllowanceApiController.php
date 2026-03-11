<?php

namespace App\Http\Controllers\Api;

use App\Models\ChildAllowance;
use Illuminate\Http\Request;

class ChildAllowanceApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = ChildAllowance::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'child_name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'from_month' => 'nullable|integer|between:1,12',
            'from_year' => 'nullable|integer|min:2000',
            'to_month' => 'nullable|integer|between:1,12',
            'to_year' => 'nullable|integer|min:2000',
        ]);
        $item = ChildAllowance::create($validated);
        return $this->successResponse($item, 'Child allowance created successfully', 201);
    }

    public function show($id)
    {
        $item = ChildAllowance::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Child allowance not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = ChildAllowance::find($id);
        if (!$item)
            return $this->errorResponse('Child allowance not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Child allowance updated successfully');
    }

    public function destroy($id)
    {
        $item = ChildAllowance::find($id);
        if (!$item)
            return $this->errorResponse('Child allowance not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Child allowance deleted successfully');
    }
}
