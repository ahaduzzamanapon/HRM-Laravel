<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bill;
use App\Models\Payment;
use App\Services\BillingService;
use Exception;
use Illuminate\Validation\ValidationException;

class ArrearBillController extends Controller
{
    protected BillingService $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Display a listing of bills and payments.
     */
    public function index(Request $request)
    {
        $query = Bill::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('billing_period')) {
            $query->where('billing_period', $request->input('billing_period'));
        }

        $bills = $query->orderBy('billing_period', 'desc')->paginate(15)->appends($request->all());
        $users = User::orderBy('name')->get();

        return view('admin.pension.arrear_bills.index', compact('bills', 'users'));
    }

    /**
     * Show form to generate a next bill or record a payment.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.pension.arrear_bills.create', compact('users'));
    }

    /**
     * Generate next bill.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'billing_periods' => 'required|array|min:1',
            'billing_periods.*' => 'required|regex:/^\d{4}-\d{2}$/',
            'base_amounts' => 'required|array|min:1',
            'base_amounts.*' => 'required|numeric|min:0',
        ]);

        try {
            $userId = (int)$request->input('user_id');
            $periods = $request->input('billing_periods');
            $amounts = $request->input('base_amounts');

            $cycles = [];
            foreach ($periods as $index => $period) {
                if (in_array($period, array_column($cycles, 'period'))) {
                    throw new Exception("Duplicate billing period '{$period}' detected in the request.");
                }
                $cycles[] = [
                    'period' => $period,
                    'amount' => $amounts[$index]
                ];
            }

            // Sort cycles chronologically (oldest first)
            usort($cycles, function ($a, $b) {
                return strcmp($a['period'], $b['period']);
            });

            \Illuminate\Support\Facades\DB::transaction(function () use ($userId, $cycles) {
                foreach ($cycles as $cycle) {
                    $this->billingService->generateNextBill(
                        $userId,
                        $cycle['amount'],
                        $cycle['period']
                    );
                }
                
                // Recalculate user's entire billing and payment ledger chronologically
                $this->billingService->recalculateUserLedger($userId);
            });

            return redirect()
                ->route('admin.pension.arrear-bills.index')
                ->with('success', count($cycles) . ' bill(s) generated successfully.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Allocate payment.
     */
    public function allocate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $result = $this->billingService->allocatePayment(
                (int)$request->input('user_id'),
                $request->input('amount')
            );

            $allocatedCount = count($result['allocated']);
            
            if ($allocatedCount === 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This user has no outstanding bills to allocate payments to.');
            }

            $msg = "Successfully allocated ৳" . number_format((float)$request->input('amount'), 2) . " across {$allocatedCount} bill(s).";
            if ((float)$result['remaining'] > 0) {
                $msg .= " Remaining unused credit: ৳" . number_format((float)$result['remaining'], 2);
            }

            return redirect()
                ->route('admin.pension.arrear-bills.index')
                ->with('success', $msg);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified bill.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'base_amount' => 'required|numeric|min:0',
            'billing_period' => 'required|regex:/^\d{4}-\d{2}$/',
        ]);

        try {
            $bill = Bill::findOrFail($id);
            
            // Check if billing period is changed and already exists for this user (excluding current bill)
            if ($request->input('billing_period') !== $bill->billing_period) {
                $exists = Bill::where('user_id', $bill->user_id)
                    ->where('billing_period', $request->input('billing_period'))
                    ->where('id', '!=', $bill->id)
                    ->exists();
                if ($exists) {
                    throw new Exception("Bill for period {$request->input('billing_period')} already exists for this user.");
                }
            }

            $bill->base_amount = $request->input('base_amount');
            $bill->billing_period = $request->input('billing_period');
            $bill->save();

            // Recalculate user's entire billing and payment ledger chronologically
            $this->billingService->recalculateUserLedger((int)$bill->user_id);

            return redirect()
                ->route('admin.pension.arrear-bills.index')
                ->with('success', 'Bill updated successfully.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete the specified bill.
     */
    public function destroy($id)
    {
        try {
            $bill = Bill::findOrFail($id);
            $userId = (int)$bill->user_id;
            $bill->delete();

            // Recalculate user's entire billing and payment ledger chronologically
            $this->billingService->recalculateUserLedger($userId);

            return redirect()
                ->route('admin.pension.arrear-bills.index')
                ->with('success', 'Bill deleted successfully.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
