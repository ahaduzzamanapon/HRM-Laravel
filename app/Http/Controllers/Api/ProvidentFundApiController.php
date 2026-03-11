<?php

namespace App\Http\Controllers\Api;

use App\Models\ProvidentFundContribution;
use App\Models\User;
use Illuminate\Http\Request;

class ProvidentFundApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = ProvidentFundContribution::with('user:id,name,last_name,emp_id')
            ->when($request->user_id, fn($q) => $q->where('employee_id', $request->user_id))
            ->when($request->month, fn($q) => $q->where('month', $request->month))
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function show($id)
    {
        $item = ProvidentFundContribution::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('PF contribution not found', 404);
        return $this->successResponse($item);
    }

    public function balance($userId)
    {
        $user = User::find($userId);
        if (!$user)
            return $this->errorResponse('Employee not found', 404);

        $contributions = ProvidentFundContribution::where('employee_id', $userId)->get();
        $summary = [
            'user_id' => $userId,
            'name' => $user->name . ' ' . $user->last_name,
            'emp_id' => $user->emp_id,
            'total_employee_contribution' => $contributions->sum('employee_contribution'),
            'total_employer_contribution' => $contributions->sum('employer_contribution'),
            'total_balance' => $contributions->sum('employee_contribution') + $contributions->sum('employer_contribution'),
            'contributions' => $contributions,
        ];
        return $this->successResponse($summary);
    }
}
