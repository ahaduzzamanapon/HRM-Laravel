<?php

namespace App\Http\Controllers\Api;

use App\Models\TransferDetail;
use Illuminate\Http\Request;

class TransferDetailApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = TransferDetail::with(['user', 'fromBranch', 'toBranch'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'transfer_date' => 'required|date',
            'from_branch_id' => 'nullable|exists:branchs,id',
            'to_branch_id' => 'nullable|exists:branchs,id',
            'from_department_id' => 'nullable|exists:departments,id',
            'to_department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);
        $item = TransferDetail::create($validated);
        return $this->successResponse($item, 'Transfer detail created successfully', 201);
    }

    public function show($id)
    {
        $item = TransferDetail::with(['user', 'fromBranch', 'toBranch'])->find($id);
        if (!$item)
            return $this->errorResponse('Transfer detail not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = TransferDetail::find($id);
        if (!$item)
            return $this->errorResponse('Transfer detail not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Transfer detail updated successfully');
    }

    public function destroy($id)
    {
        $item = TransferDetail::find($id);
        if (!$item)
            return $this->errorResponse('Transfer detail not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Transfer detail deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = TransferDetail::with(['fromBranch', 'toBranch'])->where('user_id', $userId)->orderBy('transfer_date', 'desc')->get();
        return $this->successResponse($items);
    }
}
