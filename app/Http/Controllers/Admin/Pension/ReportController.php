<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PensionEligibilityCheck;
use App\Models\PensionDisbursement;
use App\Models\Bill;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        // Default dates: start of current month to end of current month
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if ($startDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
        } else {
            $startDate = Carbon::now()->startOfMonth()->startOfDay();
        }

        if ($endDateInput) {
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } else {
            $endDate = Carbon::now()->endOfMonth()->endOfDay();
        }

        $userId = $request->input('user_id');

        // 1. Eligibility Checks (Checklist)
        $eligibilityQuery = PensionEligibilityCheck::with(['profile.user', 'checkedBy'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->orWhereBetween('checked_at', [$startDate, $endDate]);
            });

        if ($userId) {
            $eligibilityQuery->whereHas('profile', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        $eligibilityChecks = $eligibilityQuery->orderBy('created_at', 'desc')->get();

        // 2. Disbursements (Disbursement Record)
        $disbursementsQuery = PensionDisbursement::with(['profile.user'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->orWhereBetween('paid_at', [$startDate, $endDate]);
            });

        if ($userId) {
            $disbursementsQuery->whereHas('profile', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        $disbursements = $disbursementsQuery->orderBy('created_at', 'desc')->get();

        // 3. Arrear Bills (Arrear Bill)
        $billsQuery = Bill::with(['user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($userId) {
            $billsQuery->where('user_id', $userId);
        }
        $bills = $billsQuery->orderBy('created_at', 'desc')->get();

        // Summaries
        $summary = [
            'eligibility_total' => $eligibilityChecks->count(),
            'eligibility_passed' => $eligibilityChecks->where('overall_status', 'Pass')->count(),
            'eligibility_failed' => $eligibilityChecks->where('overall_status', 'Fail')->count(),
            'eligibility_pending' => $eligibilityChecks->where('overall_status', 'Pending')->count(),

            'disbursement_total_count' => $disbursements->count(),
            'disbursement_total_amount' => $disbursements->sum('net_payable'),
            'disbursement_paid_amount' => $disbursements->where('status', 'Paid')->sum('net_payable'),
            'disbursement_pending_amount' => $disbursements->where('status', 'Pending')->sum('net_payable'),

            'bills_total_count' => $bills->count(),
            'bills_total_amount' => $bills->sum('total_amount'),
            'bills_paid_amount' => $bills->sum('paid_amount'),
            'bills_due_amount' => $bills->sum('total_amount') - $bills->sum('paid_amount'),
        ];

        $users = User::orderBy('name')->get();

        return view('admin.pension.reports.index', compact(
            'eligibilityChecks',
            'disbursements',
            'bills',
            'startDate',
            'endDate',
            'userId',
            'summary',
            'users'
        ));
    }
}
