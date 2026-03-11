<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Department::with('branch')
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'nullable|exists:branchs,id',
        ]);
        $item = Department::create($validated);
        return $this->successResponse($item->load('branch'), 'Department created successfully', 201);
    }

    public function show($id)
    {
        $item = Department::with('branch')->find($id);
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
            'branch_id' => 'nullable|exists:branchs,id',
        ]);
        $item->update($validated);
        return $this->successResponse($item->load('branch'), 'Department updated successfully');
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
