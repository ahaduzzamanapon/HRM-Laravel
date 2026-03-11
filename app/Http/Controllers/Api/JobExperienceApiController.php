<?php

namespace App\Http\Controllers\Api;

use App\Models\JobExperience;
use Illuminate\Http\Request;

class JobExperienceApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = JobExperience::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'responsibilities' => 'nullable|string',
        ]);
        $item = JobExperience::create($validated);
        return $this->successResponse($item, 'Job experience created successfully', 201);
    }

    public function show($id)
    {
        $item = JobExperience::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Job experience not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = JobExperience::find($id);
        if (!$item)
            return $this->errorResponse('Job experience not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Job experience updated successfully');
    }

    public function destroy($id)
    {
        $item = JobExperience::find($id);
        if (!$item)
            return $this->errorResponse('Job experience not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Job experience deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = JobExperience::where('user_id', $userId)->get();
        return $this->successResponse($items);
    }
}
