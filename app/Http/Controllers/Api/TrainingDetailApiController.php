<?php

namespace App\Http\Controllers\Api;

use App\Models\TrainingDetail;
use Illuminate\Http\Request;

class TrainingDetailApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = TrainingDetail::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'training_name' => 'required|string|max:255',
            'institute' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);
        $item = TrainingDetail::create($validated);
        return $this->successResponse($item, 'Training detail created successfully', 201);
    }

    public function show($id)
    {
        $item = TrainingDetail::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Training detail not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = TrainingDetail::find($id);
        if (!$item)
            return $this->errorResponse('Training detail not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Training detail updated successfully');
    }

    public function destroy($id)
    {
        $item = TrainingDetail::find($id);
        if (!$item)
            return $this->errorResponse('Training detail not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Training detail deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = TrainingDetail::where('user_id', $userId)->get();
        return $this->successResponse($items);
    }
}
