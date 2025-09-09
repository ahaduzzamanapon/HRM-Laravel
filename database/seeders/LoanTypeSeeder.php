<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoanType;

class LoanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanType::create([
            'name' => 'Housing Loan',
            'description' => 'Housing loan facility for officers/employees.',
            'interest_rate' => 4.00,
            'max_installments' => null,
            'loan_ceilings' => json_encode([
                ['grade' => 'Grade 1', 'amount' => 8500000],
                ['grade' => 'Grade 2', 'amount' => 8000000],
                ['grade' => 'Grade 3', 'amount' => 6000000],
                ['grade' => 'Grade 4', 'amount' => 4500000],
                ['grade' => 'Grade 5', 'amount' => 3500000],
                ['grade' => 'Grade 6', 'amount' => 2800000],
            ]),
        ]);

        LoanType::create([
            'name' => 'Motorcycle/Scooter Loan',
            'description' => 'Motorcycle or scooter loan for permanent officers/employees.',
            'interest_rate' => 6.50,
            'max_installments' => 96,
            'loan_ceilings' => json_encode([
                ['grade' => 'All', 'amount' => 100000],
            ]),
        ]);
    }
}