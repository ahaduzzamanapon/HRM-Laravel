<?php

namespace App\Http\Controllers\Api;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;

class PayrollApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = Payroll::with(['user:id,name,last_name,emp_id'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->month, fn($q) => $q->where('month', $request->month))
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function show($id)
    {
        $item = Payroll::with('user')->find($id);
        if (!$item)
            return $this->errorResponse('Payroll record not found', 404);
        return $this->successResponse($item);
    }

    public function payslip(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $payroll = Payroll::with('user')
            ->where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if (!$payroll)
            return $this->errorResponse('Payslip not found for the given period', 404);
        return $this->successResponse($payroll);
    }

    public function salaryReport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $payrolls = Payroll::with(['user:id,name,last_name,emp_id,department_id', 'user.department:id,name'])
            ->when($request->department_id, fn($q) => $q->whereHas('user', fn($u) => $u->where('department_id', $request->department_id)))
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->get();

        $summary = [
            'month' => $request->month,
            'year' => $request->year,
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_net' => $payrolls->sum('net_salary'),
            'total_deductions' => $payrolls->sum('total_deduction'),
            'count' => $payrolls->count(),
            'payrolls' => $payrolls,
        ];

        return $this->successResponse($summary);
    }

    public function taxReport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000',
        ]);

        $payrolls = Payroll::with('user:id,name,last_name,emp_id')
            ->where('year', $request->year)
            ->get(['user_id', 'month', 'year', 'gross_salary', 'tax'])
            ->groupBy('user_id')
            ->map(fn($records, $userId) => [
                'user_id' => $userId,
                'user' => $records->first()->user,
                'total_tax' => $records->sum('tax'),
                'annual_gross' => $records->sum('gross_salary'),
            ])->values();

        return $this->successResponse($payrolls);
    }
}
