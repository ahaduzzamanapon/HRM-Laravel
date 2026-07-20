<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class BillingService
{
    /**
     * Generate the next bill for a user, rolling over any unpaid balances as arrears.
     *
     * @param int $userId
     * @param float|string $baseAmount
     * @param string $billingPeriod (e.g., '2026-07')
     * @return Bill
     * @throws Exception
     */
    public function generateNextBill(int $userId, $baseAmount, string $billingPeriod): Bill
    {
        // Validate user existence
        $user = User::find($userId);
        if (!$user) {
            throw new InvalidArgumentException("User with ID {$userId} not found.");
        }

        // Validate billing period format (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $billingPeriod)) {
            throw new InvalidArgumentException("Invalid billing period format. Expected YYYY-MM (e.g., 2026-07).");
        }

        // Check if a bill for this period already exists
        $existingBill = Bill::where('user_id', $userId)
            ->where('billing_period', $billingPeriod)
            ->first();

        if ($existingBill) {
            throw new Exception("Bill for period {$billingPeriod} already exists for user {$userId}.");
        }

        return DB::transaction(function () use ($userId, $baseAmount, $billingPeriod) {
            // Find all unpaid or partially paid bills from previous cycles
            $previousBills = Bill::where('user_id', $userId)
                ->whereIn('status', ['unpaid', 'partially_paid'])
                ->get();

            $arrearAmount = '0.00';
            foreach ($previousBills as $bill) {
                // Sum the unpaid base amount (base_amount - paid_amount) of previous bills
                $unpaidBase = $this->subtractStrings((string)$bill->base_amount, (string)$bill->paid_amount);
                $arrearAmount = $this->addStrings($arrearAmount, $unpaidBase);
            }

            $totalAmount = $this->addStrings((string)$baseAmount, $arrearAmount);

            return Bill::create([
                'user_id' => $userId,
                'billing_period' => $billingPeriod,
                'base_amount' => $baseAmount,
                'arrear_amount' => $arrearAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => '0.00',
                'status' => 'unpaid',
            ]);
        });
    }

    /**
     * Allocate a payment to the oldest outstanding bills first.
     *
     * @param int $userId
     * @param float|string $amount
     * @return array{allocated: array<array{bill_id: int, amount_paid: string}>, remaining: string}
     * @throws Exception
     */
    public function allocatePayment(int $userId, $amount): array
    {
        // Validate user existence
        $user = User::find($userId);
        if (!$user) {
            throw new InvalidArgumentException("User with ID {$userId} not found.");
        }

        if ($this->compareStrings((string)$amount, '0.00') <= 0) {
            throw new InvalidArgumentException("Payment amount must be greater than zero.");
        }

        return DB::transaction(function () use ($userId, $amount) {
            $remainingPayment = (string)$amount;
            $allocations = [];

            // Fetch outstanding bills ordered by billing period ASC (oldest first)
            $outstandingBills = Bill::where('user_id', $userId)
                ->whereIn('status', ['unpaid', 'partially_paid'])
                ->orderBy('billing_period', 'asc')
                ->lockForUpdate() // Lock records for update to prevent race conditions
                ->get();

            foreach ($outstandingBills as $bill) {
                if ($this->compareStrings($remainingPayment, '0.00') <= 0) {
                    break;
                }

                // Owed amount is base_amount - paid_amount
                $owedAmount = $this->subtractStrings((string)$bill->base_amount, (string)$bill->paid_amount);

                if ($this->compareStrings($owedAmount, '0.00') <= 0) {
                    continue;
                }

                // Determine allocation amount
                $allocation = $this->compareStrings($remainingPayment, $owedAmount) >= 0 
                    ? $owedAmount 
                    : $remainingPayment;

                // Update bill figures
                $newPaidAmount = $this->addStrings((string)$bill->paid_amount, $allocation);
                $bill->paid_amount = $newPaidAmount;
                
                // Set status based on base_amount
                if ($this->compareStrings($newPaidAmount, (string)$bill->base_amount) >= 0) {
                    $bill->status = 'paid';
                } else {
                    $bill->status = 'partially_paid';
                }

                $bill->save();

                // Record payment transaction
                Payment::create([
                    'user_id' => $userId,
                    'bill_id' => $bill->id,
                    'amount' => $allocation,
                    'paid_at' => now(),
                ]);

                $allocations[] = [
                    'bill_id' => $bill->id,
                    'amount_paid' => $allocation,
                ];

                $remainingPayment = $this->subtractStrings($remainingPayment, $allocation);
            }

            return [
                'allocated' => $allocations,
                'remaining' => $remainingPayment,
            ];
        });
    }

    /**
     * Recalculate a user's entire billing and payment ledger chronologically.
     *
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public function recalculateUserLedger(int $userId): void
    {
        DB::transaction(function () use ($userId) {
            // 1. Fetch all bills and payments
            $bills = Bill::where('user_id', $userId)->orderBy('billing_period', 'asc')->get();
            $payments = Payment::where('user_id', $userId)->orderBy('paid_at', 'asc')->orderBy('id', 'asc')->get();

            // Save payments in memory
            $paymentRecords = [];
            foreach ($payments as $p) {
                $paymentRecords[] = [
                    'amount' => (string)$p->amount,
                    'paid_at' => $p->paid_at
                ];
            }

            // Delete all existing payments for the user to clean the slate
            Payment::where('user_id', $userId)->delete();

            // 2. Reset all bills to base state
            foreach ($bills as $bill) {
                $bill->paid_amount = '0.00';
                $bill->arrear_amount = '0.00';
                $bill->total_amount = $bill->base_amount;
                $bill->status = 'unpaid';
                $bill->save();
            }

            // 3. Construct chronological event list
            // Events can be Bill generations or Payments.
            // We represent a bill generation by its billing_period (interpreted as YYYY-MM-01).
            $events = [];

            foreach ($bills as $bill) {
                $events[] = [
                    'type' => 'bill',
                    'date' => $bill->billing_period . '-01',
                    'model' => $bill
                ];
            }

            foreach ($paymentRecords as $p) {
                $events[] = [
                    'type' => 'payment',
                    'date' => is_string($p['paid_at']) ? $p['paid_at'] : $p['paid_at']->toDateTimeString(),
                    'amount' => $p['amount'],
                    'paid_at' => $p['paid_at']
                ];
            }

            // Sort events by date. If dates are equal, bills should come before payments
            usort($events, function ($a, $b) {
                $cmp = strcmp($a['date'], $b['date']);
                if ($cmp !== 0) {
                    return $cmp;
                }
                if ($a['type'] === $b['type']) {
                    return 0;
                }
                return $a['type'] === 'bill' ? -1 : 1;
            });

            // 4. Process the timeline
            $activeBills = []; // Bills that have been generated so far

            foreach ($events as $event) {
                if ($event['type'] === 'bill') {
                    $bill = $event['model'];
                    
                    // Recalculate arrear_amount: sum of unpaid base of all active bills generated BEFORE this one
                    $arrearAmount = '0.00';
                    foreach ($activeBills as $prevBill) {
                        $unpaid = $this->subtractStrings((string)$prevBill->base_amount, (string)$prevBill->paid_amount);
                        $arrearAmount = $this->addStrings($arrearAmount, $unpaid);
                    }

                    $bill->arrear_amount = $arrearAmount;
                    $bill->total_amount = $this->addStrings((string)$bill->base_amount, $arrearAmount);
                    
                    // Re-evaluate status based on current paid_amount vs base_amount
                    if ($this->compareStrings((string)$bill->paid_amount, (string)$bill->base_amount) >= 0) {
                        $bill->status = 'paid';
                    } elseif ($this->compareStrings((string)$bill->paid_amount, '0.00') > 0) {
                        $bill->status = 'partially_paid';
                    } else {
                        $bill->status = 'unpaid';
                    }
                    
                    $bill->save();

                    // Add this bill to the list of active bills
                    $activeBills[] = $bill;

                } elseif ($event['type'] === 'payment') {
                    $amount = $event['amount'];
                    $paidAt = $event['paid_at'];

                    // Allocate the payment to active bills in order of billing_period ASC (oldest first)
                    $remainingPayment = $amount;

                    foreach ($activeBills as $bill) {
                        if ($this->compareStrings($remainingPayment, '0.00') <= 0) {
                            break;
                        }

                        $owedAmount = $this->subtractStrings((string)$bill->base_amount, (string)$bill->paid_amount);
                        if ($this->compareStrings($owedAmount, '0.00') <= 0) {
                            continue;
                        }

                        $allocation = $this->compareStrings($remainingPayment, $owedAmount) >= 0 
                            ? $owedAmount 
                            : $remainingPayment;

                        $newPaidAmount = $this->addStrings((string)$bill->paid_amount, $allocation);
                        $bill->paid_amount = $newPaidAmount;

                        if ($this->compareStrings($newPaidAmount, (string)$bill->base_amount) >= 0) {
                            $bill->status = 'paid';
                        } else {
                            $bill->status = 'partially_paid';
                        }

                        $bill->save();

                        // Record the payment allocation with the original paid_at date
                        Payment::create([
                            'user_id' => $userId,
                            'bill_id' => $bill->id,
                            'amount' => $allocation,
                            'paid_at' => $paidAt,
                        ]);

                        $remainingPayment = $this->subtractStrings($remainingPayment, $allocation);
                    }
                }
            }
        });
    }

    /**
     * Helper to perform precise decimal addition.
     */
    private function addStrings(string $a, string $b): string
    {
        if (function_exists('bcadd')) {
            return bcadd($a, $b, 2);
        }
        return number_format((float)$a + (float)$b, 2, '.', '');
    }

    /**
     * Helper to perform precise decimal subtraction.
     */
    private function subtractStrings(string $a, string $b): string
    {
        if (function_exists('bcsub')) {
            return bcsub($a, $b, 2);
        }
        return number_format((float)$a - (float)$b, 2, '.', '');
    }

    /**
     * Helper to perform precise decimal comparison.
     * Returns:
     *   0 if equal
     *   1 if $a > $b
     *  -1 if $a < $b
     */
    private function compareStrings(string $a, string $b): int
    {
        if (function_exists('bccomp')) {
            return bccomp($a, $b, 2);
        }
        $valA = (float)$a;
        $valB = (float)$b;
        if (abs($valA - $valB) < 0.00001) {
            return 0;
        }
        return $valA > $valB ? 1 : -1;
    }
}
