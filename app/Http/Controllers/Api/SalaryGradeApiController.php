<?php

namespace App\Http\Controllers\Api;

use App\Models\SalaryGrade;
use Illuminate\Http\Request;

class SalaryGradeApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = SalaryGrade::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'starting_salary' => 'required|numeric|min:0',
        ]);
        $item = SalaryGrade::create($validated);
        return $this->successResponse($item, 'Salary grade created successfully', 201);
    }

    public function show($id)
    {
        $item = SalaryGrade::find($id);
        if (!$item)
            return $this->errorResponse('Salary grade not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = SalaryGrade::find($id);
        if (!$item)
            return $this->errorResponse('Salary grade not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Salary grade updated successfully');
    }

    public function destroy($id)
    {
        $item = SalaryGrade::find($id);
        if (!$item)
            return $this->errorResponse('Salary grade not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Salary grade deleted successfully');
    }
}
