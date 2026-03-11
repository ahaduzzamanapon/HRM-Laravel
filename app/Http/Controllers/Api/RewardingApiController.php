<?php

namespace App\Http\Controllers\Api;

use App\Models\Rewarding;
use Illuminate\Http\Request;

class RewardingApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Rewarding::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        $item = Rewarding::create($validated);
        return $this->successResponse($item, 'Rewarding created successfully', 201);
    }

    public function show($id)
    {
        $item = Rewarding::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Rewarding not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Rewarding::find($id);
        if (!$item)
            return $this->errorResponse('Rewarding not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Rewarding updated successfully');
    }

    public function destroy($id)
    {
        $item = Rewarding::find($id);
        if (!$item)
            return $this->errorResponse('Rewarding not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Rewarding deleted successfully');
    }
}
