<?php

namespace App\Http\Controllers\Api;

use App\Models\SalaryIncrement;
use Illuminate\Http\Request;

class SalaryIncrementApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = SalaryIncrement::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'increment_date' => 'required|date',
            'increment_amount' => 'required|numeric|min:0',
            'new_basic' => 'nullable|numeric|min:0',
            'new_gross' => 'nullable|numeric|min:0',
            'grade' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $item = SalaryIncrement::create($validated);
        return $this->successResponse($item, 'Salary increment created successfully', 201);
    }

    public function show($id)
    {
        $item = SalaryIncrement::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Salary increment not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = SalaryIncrement::find($id);
        if (!$item)
            return $this->errorResponse('Salary increment not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Salary increment updated successfully');
    }

    public function destroy($id)
    {
        $item = SalaryIncrement::find($id);
        if (!$item)
            return $this->errorResponse('Salary increment not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Salary increment deleted successfully');
    }

    public function listByUser($userId)
    {
        $items = SalaryIncrement::where('user_id', $userId)->orderBy('increment_date', 'desc')->get();
        return $this->successResponse($items);
    }
}
