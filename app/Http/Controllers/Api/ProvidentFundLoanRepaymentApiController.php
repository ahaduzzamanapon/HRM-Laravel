<?php

namespace App\Http\Controllers\Api;

use App\Models\ProvidentFundLoanRepayment;
use Illuminate\Http\Request;

class ProvidentFundLoanRepaymentApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = ProvidentFundLoanRepayment::with('providentFundLoan.user')
            ->when($request->loan_id, fn($q) => $q->where('provident_fund_loan_id', $request->loan_id))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provident_fund_loan_id' => 'required|exists:provident_fund_loans,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'required|date',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);
        $item = ProvidentFundLoanRepayment::create($validated);
        return $this->successResponse($item, 'PF Loan repayment recorded', 201);
    }

    public function show($id)
    {
        $item = ProvidentFundLoanRepayment::with('providentFundLoan.user')->find($id);
        if (!$item)
            return $this->errorResponse('Repayment not found', 404);
        return $this->successResponse($item);
    }

    public function destroy($id)
    {
        $item = ProvidentFundLoanRepayment::find($id);
        if (!$item)
            return $this->errorResponse('Repayment not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Repayment deleted successfully');
    }
}
