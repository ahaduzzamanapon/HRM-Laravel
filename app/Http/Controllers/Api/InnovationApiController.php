<?php

namespace App\Http\Controllers\Api;

use App\Models\Innovation;
use Illuminate\Http\Request;

class InnovationApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Innovation::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
        ]);
        $item = Innovation::create($validated);
        return $this->successResponse($item, 'Innovation created successfully', 201);
    }

    public function show($id)
    {
        $item = Innovation::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Innovation not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Innovation::find($id);
        if (!$item)
            return $this->errorResponse('Innovation not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Innovation updated successfully');
    }

    public function destroy($id)
    {
        $item = Innovation::find($id);
        if (!$item)
            return $this->errorResponse('Innovation not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Innovation deleted successfully');
    }
}
