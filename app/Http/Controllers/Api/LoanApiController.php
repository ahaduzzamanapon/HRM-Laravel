<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Loan::with(['user:id,name,last_name,emp_id', 'loanType'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->loan_type_id, fn($q) => $q->where('loan_type_id', $request->loan_type_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:1',
            'installments' => 'required|integer|min:1',
            'application_date' => 'nullable|date',
            'reason' => 'nullable|string',
        ]);
        $validated['status'] = 'pending';
        $item = Loan::create($validated);
        return $this->successResponse($item->load(['user', 'loanType']), 'Loan application submitted', 201);
    }

    public function show($id)
    {
        $item = Loan::with(['user', 'loanType', 'repayments'])->find($id);
        if (!$item)
            return $this->errorResponse('Loan not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Loan::find($id);
        if (!$item)
            return $this->errorResponse('Loan not found', 404);
        $item->update($request->all());
        return $this->successResponse($item->load(['user', 'loanType']), 'Loan updated successfully');
    }

    public function destroy($id)
    {
        $item = Loan::find($id);
        if (!$item)
            return $this->errorResponse('Loan not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Loan deleted successfully');
    }
}
