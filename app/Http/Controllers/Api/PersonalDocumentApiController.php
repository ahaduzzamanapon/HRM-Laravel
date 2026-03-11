<?php

namespace App\Http\Controllers\Api;

use App\Models\PersonalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonalDocumentApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = PersonalDocument::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'document_name' => 'required|string|max:255',
            'document_type' => 'nullable|string|max:100',
            'file' => 'nullable|file|max:5120',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('personal_documents', 'public');
        }

        $item = PersonalDocument::create($validated);
        return $this->successResponse($item, 'Personal document created successfully', 201);
    }

    public function show($id)
    {
        $item = PersonalDocument::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Personal document not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = PersonalDocument::find($id);
        if (!$item)
            return $this->errorResponse('Personal document not found', 404);

        $data = $request->except('file');
        if ($request->hasFile('file')) {
            if ($item->file_path)
                Storage::disk('public')->delete($item->file_path);
            $data['file_path'] = $request->file('file')->store('personal_documents', 'public');
        }

        $item->update($data);
        return $this->successResponse($item, 'Personal document updated successfully');
    }

    public function destroy($id)
    {
        $item = PersonalDocument::find($id);
        if (!$item)
            return $this->errorResponse('Personal document not found', 404);
        if ($item->file_path)
            Storage::disk('public')->delete($item->file_path);
        $item->delete();
        return $this->successResponse(null, 'Personal document deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = PersonalDocument::where('user_id', $userId)->get();
        return $this->successResponse($items);
    }
}
