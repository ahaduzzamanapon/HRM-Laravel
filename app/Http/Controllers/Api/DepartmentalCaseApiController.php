<?php

namespace App\Http\Controllers\Api;

use App\Models\DepartmentalCase;
use Illuminate\Http\Request;

class DepartmentalCaseApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = DepartmentalCase::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'case_no' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);
        $item = DepartmentalCase::create($validated);
        return $this->successResponse($item, 'Departmental case created successfully', 201);
    }

    public function show($id)
    {
        $item = DepartmentalCase::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Departmental case not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = DepartmentalCase::find($id);
        if (!$item)
            return $this->errorResponse('Departmental case not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Departmental case updated successfully');
    }

    public function destroy($id)
    {
        $item = DepartmentalCase::find($id);
        if (!$item)
            return $this->errorResponse('Departmental case not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Departmental case deleted successfully');
    }
}
