<?php

namespace App\Http\Controllers\Api;

use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanTypeApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = LoanType::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_amount' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'max_installments' => 'nullable|integer|min:1',
        ]);
        $item = LoanType::create($validated);
        return $this->successResponse($item, 'Loan type created successfully', 201);
    }

    public function show($id)
    {
        $item = LoanType::find($id);
        if (!$item)
            return $this->errorResponse('Loan type not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = LoanType::find($id);
        if (!$item)
            return $this->errorResponse('Loan type not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Loan type updated successfully');
    }

    public function destroy($id)
    {
        $item = LoanType::find($id);
        if (!$item)
            return $this->errorResponse('Loan type not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Loan type deleted successfully');
    }
}
