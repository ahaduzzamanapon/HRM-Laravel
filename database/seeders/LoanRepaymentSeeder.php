<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\LoanRepayment;

class LoanRepaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loans = Loan::all();

        if ($loans->isEmpty()) {
            $this->command->info('Skipping LoanRepaymentSeeder: No loans found.');
            return;
        }

        foreach ($loans as $loan) {
            // Add a few sample repayments for each loan
            for ($i = 1; $i <= 3; $i++) {
                LoanRepayment::create([
                    'loan_id' => $loan->id,
                    'amount' => $loan->monthly_installment,
                    'repayment_date' => now()->subMonths($i),
                    'remarks' => 'Monthly installment #' . $i,
                ]);
            }
        }
    }
}