<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\User;
use App\Models\LoanType;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $loanTypes = LoanType::all();

        if ($users->isEmpty() || $loanTypes->isEmpty()) {
            $this->command->info('Skipping LoanSeeder: No users or loan types found.');
            return;
        }

        // Example: Create a Housing Loan for the first user
        $housingLoanType = $loanTypes->where('name', 'Housing Loan')->first();
        if ($housingLoanType) {
            Loan::create([
                'employee_id' => $users->first()->id,
                'loan_type_id' => $housingLoanType->id,
                'amount' => 5000000.00,
                'interest_rate' => $housingLoanType->interest_rate,
                'installments' => 120,
                'monthly_installment' => 41666.67, // Example calculation
                'disbursement_date' => now()->subMonths(2),
                'next_payment_date' => now()->addMonth(),
                'outstanding_balance' => 5000000.00,
                'status' => 'Disbursed',
                'remarks' => 'Initial housing loan for employee.',
            ]);
        }

        // Example: Create a Motorcycle Loan for the second user (if available)
        $motorcycleLoanType = $loanTypes->where('name', 'Motorcycle/Scooter Loan')->first();
        if ($motorcycleLoanType && $users->count() > 1) {
            Loan::create([
                'employee_id' => $users->skip(1)->first()->id,
                'loan_type_id' => $motorcycleLoanType->id,
                'amount' => 100000.00,
                'interest_rate' => $motorcycleLoanType->interest_rate,
                'installments' => 96,
                'monthly_installment' => 1250.00,
                'disbursement_date' => now()->subMonth(),
                'next_payment_date' => now()->addMonth(),
                'outstanding_balance' => 100000.00,
                'status' => 'Disbursed',
                'remarks' => 'Initial motorcycle loan for employee.',
            ]);
        }
    }
}