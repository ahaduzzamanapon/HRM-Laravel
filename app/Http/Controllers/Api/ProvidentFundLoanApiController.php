<?php

namespace App\Http\Controllers\Api;

use App\Models\ProvidentFundLoan;
use Illuminate\Http\Request;

class ProvidentFundLoanApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = ProvidentFundLoan::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'installments' => 'required|integer|min:1',
            'application_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);
        $validated['status'] = 'pending';
        $item = ProvidentFundLoan::create($validated);
        return $this->successResponse($item->load('user'), 'PF Loan application submitted', 201);
    }

    public function show($id)
    {
        $item = ProvidentFundLoan::with(['user', 'repayments'])->find($id);
        if (!$item)
            return $this->errorResponse('PF Loan not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = ProvidentFundLoan::find($id);
        if (!$item)
            return $this->errorResponse('PF Loan not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'PF Loan updated successfully');
    }

    public function destroy($id)
    {
        $item = ProvidentFundLoan::find($id);
        if (!$item)
            return $this->errorResponse('PF Loan not found', 404);
        $item->delete();
        return $this->successResponse(null, 'PF Loan deleted successfully');
    }
}
