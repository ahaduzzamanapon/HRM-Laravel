<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $branches = Branch::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($branches);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);
        $branch = Branch::create($validated);
        return $this->successResponse($branch, 'Branch created successfully', 201);
    }

    public function show($id)
    {
        $branch = Branch::find($id);
        if (!$branch)
            return $this->errorResponse('Branch not found', 404);
        return $this->successResponse($branch);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);
        if (!$branch)
            return $this->errorResponse('Branch not found', 404);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);
        $branch->update($validated);
        return $this->successResponse($branch, 'Branch updated successfully');
    }

    public function destroy($id)
    {
        $branch = Branch::find($id);
        if (!$branch)
            return $this->errorResponse('Branch not found', 404);
        $branch->delete();
        return $this->successResponse(null, 'Branch deleted successfully');
    }
}
