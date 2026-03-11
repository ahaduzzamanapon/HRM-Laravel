<?php

namespace App\Http\Controllers\Api;

use App\Models\PromotionDetail;
use Illuminate\Http\Request;

class PromotionDetailApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = PromotionDetail::with(['user', 'designation'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'designation_id' => 'nullable|exists:designations,id',
            'promotion_date' => 'required|date',
            'increment_amount' => 'nullable|numeric|min:0',
            'grade' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $item = PromotionDetail::create($validated);
        return $this->successResponse($item->load(['user', 'designation']), 'Promotion detail created successfully', 201);
    }

    public function show($id)
    {
        $item = PromotionDetail::with(['user', 'designation'])->find($id);
        if (!$item)
            return $this->errorResponse('Promotion detail not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = PromotionDetail::find($id);
        if (!$item)
            return $this->errorResponse('Promotion detail not found', 404);
        $item->update($request->all());
        return $this->successResponse($item->load(['user', 'designation']), 'Promotion detail updated successfully');
    }

    public function destroy($id)
    {
        $item = PromotionDetail::find($id);
        if (!$item)
            return $this->errorResponse('Promotion detail not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Promotion detail deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = PromotionDetail::with('designation')->where('user_id', $userId)->orderBy('promotion_date', 'desc')->get();
        return $this->successResponse($items);
    }
}
