<?php

namespace App\Http\Controllers\Api;

use App\Models\FuneralSupport;
use Illuminate\Http\Request;

class FuneralSupportApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = FuneralSupport::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'deceased_name' => 'required|string|max:255',
            'relation' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        $item = FuneralSupport::create($validated);
        return $this->successResponse($item, 'Funeral support created successfully', 201);
    }

    public function show($id)
    {
        $item = FuneralSupport::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Funeral support not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = FuneralSupport::find($id);
        if (!$item)
            return $this->errorResponse('Funeral support not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Funeral support updated successfully');
    }

    public function destroy($id)
    {
        $item = FuneralSupport::find($id);
        if (!$item)
            return $this->errorResponse('Funeral support not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Funeral support deleted successfully');
    }
}
