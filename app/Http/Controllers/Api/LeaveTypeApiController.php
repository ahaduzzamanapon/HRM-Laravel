<?php

namespace App\Http\Controllers\Api;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = LeaveType::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'days' => 'required|integer|min:0',
            'short_name' => 'nullable|string|max:10',
        ]);
        $item = LeaveType::create($validated);
        return $this->successResponse($item, 'Leave type created successfully', 201);
    }

    public function show($id)
    {
        $item = LeaveType::find($id);
        if (!$item)
            return $this->errorResponse('Leave type not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = LeaveType::find($id);
        if (!$item)
            return $this->errorResponse('Leave type not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Leave type updated successfully');
    }

    public function destroy($id)
    {
        $item = LeaveType::find($id);
        if (!$item)
            return $this->errorResponse('Leave type not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Leave type deleted successfully');
    }
}
