<?php

namespace App\Http\Controllers\Api;

use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltyApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Penalty::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reason' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $item = Penalty::create($validated);
        return $this->successResponse($item, 'Penalty created successfully', 201);
    }

    public function show($id)
    {
        $item = Penalty::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Penalty not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Penalty::find($id);
        if (!$item)
            return $this->errorResponse('Penalty not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Penalty updated successfully');
    }

    public function destroy($id)
    {
        $item = Penalty::find($id);
        if (!$item)
            return $this->errorResponse('Penalty not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Penalty deleted successfully');
    }
}
