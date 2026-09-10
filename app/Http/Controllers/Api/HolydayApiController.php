<?php

namespace App\Http\Controllers\Api;

use App\Models\Holyday;
use Illuminate\Http\Request;

class HolydayApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Holyday::when($request->year, fn($q) => $q->whereYear('date', $request->year))
            ->when($request->month, fn($q) => $q->whereMonth('date', $request->month))
            ->orderBy('date')
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
        ]);
        $item = Holyday::create($validated);
        return $this->successResponse($item, 'Holiday created successfully', 201);
    }

    public function show($id)
    {
        $item = Holyday::find($id);
        if (!$item)
            return $this->errorResponse('Holiday not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Holyday::find($id);
        if (!$item)
            return $this->errorResponse('Holiday not found', 404);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
        ]);
        $item->update($validated);
        return $this->successResponse($item, 'Holiday updated successfully');
    }

    public function destroy($id)
    {
        $item = Holyday::find($id);
        if (!$item)
            return $this->errorResponse('Holiday not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Holiday deleted successfully');
    }
}
