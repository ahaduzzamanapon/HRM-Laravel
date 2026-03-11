<?php

namespace App\Http\Controllers\Api;

use App\Models\Shift;
use App\Models\ShiftDetail;
use Illuminate\Http\Request;

class ShiftApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Shift::with('shiftDetails')
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'friday' => 'nullable|boolean',
            'saturday' => 'nullable|boolean',
        ]);
        $item = Shift::create($validated);
        return $this->successResponse($item->load('shiftDetails'), 'Shift created successfully', 201);
    }

    public function show($id)
    {
        $item = Shift::with('shiftDetails')->find($id);
        if (!$item)
            return $this->errorResponse('Shift not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Shift::find($id);
        if (!$item)
            return $this->errorResponse('Shift not found', 404);
        $item->update($request->all());
        return $this->successResponse($item->load('shiftDetails'), 'Shift updated successfully');
    }

    public function destroy($id)
    {
        $item = Shift::find($id);
        if (!$item)
            return $this->errorResponse('Shift not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Shift deleted successfully');
    }
}
