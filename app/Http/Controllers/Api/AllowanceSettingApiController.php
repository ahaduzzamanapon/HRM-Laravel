<?php

namespace App\Http\Controllers\Api;

use App\Models\AllowanceSetting;
use Illuminate\Http\Request;

class AllowanceSettingApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = AllowanceSetting::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'percentage' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'status' => 'nullable|boolean',
        ]);
        $item = AllowanceSetting::create($validated);
        return $this->successResponse($item, 'Allowance setting created successfully', 201);
    }

    public function show($id)
    {
        $item = AllowanceSetting::find($id);
        if (!$item)
            return $this->errorResponse('Allowance setting not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = AllowanceSetting::find($id);
        if (!$item)
            return $this->errorResponse('Allowance setting not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Allowance setting updated successfully');
    }

    public function destroy($id)
    {
        $item = AllowanceSetting::find($id);
        if (!$item)
            return $this->errorResponse('Allowance setting not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Allowance setting deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = AllowanceSetting::whereHas('userAllowances', fn($q) => $q->where('user_id', $userId))->get();
        return $this->successResponse($items);
    }
}
