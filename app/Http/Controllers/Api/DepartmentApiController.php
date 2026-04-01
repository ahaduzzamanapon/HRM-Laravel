<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Department::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);
        $validated['status'] = $validated['status'] ?? 'Active';
        $item = Department::create($validated);
        return $this->successResponse($item, 'Department created successfully', 201);
    }

    public function show($id)
    {
        $item = Department::find($id);
        if (!$item)
            return $this->errorResponse('Department not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Department::find($id);
        if (!$item)
            return $this->errorResponse('Department not found', 404);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);
        $item->update($validated);
        return $this->successResponse($item, 'Department updated successfully');
    }

    public function destroy($id)
    {
        $item = Department::find($id);
        if (!$item)
            return $this->errorResponse('Department not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Department deleted successfully');
    }
}
