<?php

namespace App\Http\Controllers\Api;

use App\Models\EmployeeChildrenEducationSupport;
use Illuminate\Http\Request;

class EmployeeChildrenEducationSupportApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = EmployeeChildrenEducationSupport::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'child_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2000',
            'description' => 'nullable|string',
        ]);
        $item = EmployeeChildrenEducationSupport::create($validated);
        return $this->successResponse($item, 'Education support created successfully', 201);
    }

    public function show($id)
    {
        $item = EmployeeChildrenEducationSupport::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Education support not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = EmployeeChildrenEducationSupport::find($id);
        if (!$item)
            return $this->errorResponse('Education support not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Education support updated successfully');
    }

    public function destroy($id)
    {
        $item = EmployeeChildrenEducationSupport::find($id);
        if (!$item)
            return $this->errorResponse('Education support not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Education support deleted successfully');
    }
}
