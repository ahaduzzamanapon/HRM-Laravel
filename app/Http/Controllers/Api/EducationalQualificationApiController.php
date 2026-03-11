<?php

namespace App\Http\Controllers\Api;

use App\Models\EducationalQualification;
use Illuminate\Http\Request;

class EducationalQualificationApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = EducationalQualification::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'degree' => 'required|string|max:255',
            'institute' => 'nullable|string|max:255',
            'passing_year' => 'nullable|integer',
            'result' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:100',
        ]);
        $item = EducationalQualification::create($validated);
        return $this->successResponse($item, 'Educational qualification created successfully', 201);
    }

    public function show($id)
    {
        $item = EducationalQualification::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Educational qualification not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = EducationalQualification::find($id);
        if (!$item)
            return $this->errorResponse('Educational qualification not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Educational qualification updated successfully');
    }

    public function destroy($id)
    {
        $item = EducationalQualification::find($id);
        if (!$item)
            return $this->errorResponse('Educational qualification not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Educational qualification deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = EducationalQualification::where('user_id', $userId)->get();
        return $this->successResponse($items);
    }
}
