<?php

namespace App\Http\Controllers\Api;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Designation::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $item = Designation::create($validated);
        return $this->successResponse($item, 'Designation created successfully', 201);
    }

    public function show($id)
    {
        $item = Designation::find($id);
        if (!$item)
            return $this->errorResponse('Designation not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Designation::find($id);
        if (!$item)
            return $this->errorResponse('Designation not found', 404);
        $validated = $request->validate(['name' => 'sometimes|required|string|max:255']);
        $item->update($validated);
        return $this->successResponse($item, 'Designation updated successfully');
    }

    public function destroy($id)
    {
        $item = Designation::find($id);
        if (!$item)
            return $this->errorResponse('Designation not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Designation deleted successfully');
    }
}
