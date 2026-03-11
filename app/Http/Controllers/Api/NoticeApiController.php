<?php

namespace App\Http\Controllers\Api;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Notice::with('user:id,name,last_name')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:20',
            'date' => 'nullable|date',
        ]);
        $validated['user_id'] = $request->user()->id;
        $item = Notice::create($validated);
        return $this->successResponse($item, 'Notice created successfully', 201);
    }

    public function show($id)
    {
        $item = Notice::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Notice not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Notice::find($id);
        if (!$item)
            return $this->errorResponse('Notice not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Notice updated successfully');
    }

    public function destroy($id)
    {
        $item = Notice::find($id);
        if (!$item)
            return $this->errorResponse('Notice not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Notice deleted successfully');
    }
}
