<?php

namespace App\Http\Controllers\Api;

use App\Models\LoanRepayment;
use Illuminate\Http\Request;

class LoanRepaymentApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = LoanRepayment::with('loan.user')
            ->when($request->loan_id, fn($q) => $q->where('loan_id', $request->loan_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'required|date',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);
        $item = LoanRepayment::create($validated);
        return $this->successResponse($item, 'Loan repayment recorded', 201);
    }

    public function show($id)
    {
        $item = LoanRepayment::with('loan.user')->find($id);
        if (!$item)
            return $this->errorResponse('Repayment not found', 404);
        return $this->successResponse($item);
    }

    public function destroy($id)
    {
        $item = LoanRepayment::find($id);
        if (!$item)
            return $this->errorResponse('Repayment not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Repayment deleted successfully');
    }
}
