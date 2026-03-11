<?php

namespace App\Http\Controllers\Api;

use App\Models\MedicalSupport;
use Illuminate\Http\Request;

class MedicalSupportApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = MedicalSupport::with('user:id,name,last_name,emp_id')
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
            'description' => 'nullable|string',
        ]);
        $item = MedicalSupport::create($validated);
        return $this->successResponse($item, 'Medical support created successfully', 201);
    }

    public function show($id)
    {
        $item = MedicalSupport::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Medical support not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = MedicalSupport::find($id);
        if (!$item)
            return $this->errorResponse('Medical support not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Medical support updated successfully');
    }

    public function destroy($id)
    {
        $item = MedicalSupport::find($id);
        if (!$item)
            return $this->errorResponse('Medical support not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Medical support deleted successfully');
    }
}
