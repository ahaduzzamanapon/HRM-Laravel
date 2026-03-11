<?php

namespace App\Http\Controllers\Api;

use App\Models\Movement;
use Illuminate\Http\Request;

class MovementApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $items = Movement::with(['user'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when(!$user->group_id || $user->group_id > 2, fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('from_date')
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'purpose' => 'required|string',
            'place' => 'nullable|string|max:255',
            'status' => 'nullable|string',
        ]);
        $validated['status'] = $validated['status'] ?? 'pending';
        $item = Movement::create($validated);
        return $this->successResponse($item->load('user'), 'Movement created successfully', 201);
    }

    public function show($id)
    {
        $item = Movement::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Movement not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Movement::find($id);
        if (!$item)
            return $this->errorResponse('Movement not found', 404);
        $item->update($request->all());
        return $this->successResponse($item->load('user'), 'Movement updated successfully');
    }

    public function destroy($id)
    {
        $item = Movement::find($id);
        if (!$item)
            return $this->errorResponse('Movement not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Movement deleted successfully');
    }
}
