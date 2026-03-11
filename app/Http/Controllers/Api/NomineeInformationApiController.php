<?php

namespace App\Http\Controllers\Api;

use App\Models\NomineeInformation;
use Illuminate\Http\Request;

class NomineeInformationApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = NomineeInformation::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'relation' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ]);
        $item = NomineeInformation::create($validated);
        return $this->successResponse($item, 'Nominee information created successfully', 201);
    }

    public function show($id)
    {
        $item = NomineeInformation::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Nominee information not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = NomineeInformation::find($id);
        if (!$item)
            return $this->errorResponse('Nominee information not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Nominee information updated successfully');
    }

    public function destroy($id)
    {
        $item = NomineeInformation::find($id);
        if (!$item)
            return $this->errorResponse('Nominee information not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Nominee information deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = NomineeInformation::where('user_id', $userId)->get();
        return $this->successResponse($items);
    }
}
