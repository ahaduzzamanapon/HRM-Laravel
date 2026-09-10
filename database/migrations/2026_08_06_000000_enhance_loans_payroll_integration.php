<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'effective_month')) {
                $table->date('effective_month')->nullable()->after('disbursement_date');
            }
            if (!Schema::hasColumn('loans', 'required_month')) {
                $table->date('required_month')->nullable()->after('effective_month');
            }
            if (!Schema::hasColumn('loans', 'paid_installments')) {
                $table->integer('paid_installments')->default(0)->after('installments');
            }
            if (!Schema::hasColumn('loans', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('outstanding_balance');
            }
            if (!Schema::hasColumn('loans', 'last_deduction_month')) {
                $table->date('last_deduction_month')->nullable()->after('paid_amount');
            }
            if (!Schema::hasColumn('loans', 'next_deduction_month')) {
                $table->date('next_deduction_month')->nullable()->after('last_deduction_month');
            }
        });

        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE loans MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Pending'");
        } catch (\Exception $e) {
            // Ignore if already string
        }

        Schema::table('loan_repayments', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_repayments', 'employee_id')) {
                $table->unsignedInteger('employee_id')->nullable()->after('loan_id');
            }
            if (!Schema::hasColumn('loan_repayments', 'payroll_month')) {
                $table->date('payroll_month')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('loan_repayments', 'installment_amount')) {
                $table->decimal('installment_amount', 15, 2)->default(0)->after('payroll_month');
            }
            if (!Schema::hasColumn('loan_repayments', 'principal_paid')) {
                $table->decimal('principal_paid', 15, 2)->default(0)->after('installment_amount');
            }
            if (!Schema::hasColumn('loan_repayments', 'interest_paid')) {
                $table->decimal('interest_paid', 15, 2)->default(0)->after('principal_paid');
            }
            if (!Schema::hasColumn('loan_repayments', 'remaining_balance')) {
                $table->decimal('remaining_balance', 15, 2)->default(0)->after('interest_paid');
            }
            if (!Schema::hasColumn('loan_repayments', 'salary_sheet_reference')) {
                $table->unsignedBigInteger('salary_sheet_reference')->nullable()->after('remaining_balance');
            }
            if (!Schema::hasColumn('loan_repayments', 'payroll_batch_id')) {
                $table->string('payroll_batch_id')->nullable()->after('salary_sheet_reference');
            }
            if (!Schema::hasColumn('loan_repayments', 'created_by')) {
                $table->unsignedInteger('created_by')->nullable()->after('payroll_batch_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $columns = ['effective_month', 'required_month', 'paid_installments', 'paid_amount', 'last_deduction_month', 'next_deduction_month'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('loans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('loan_repayments', function (Blueprint $table) {
            $columns = ['employee_id', 'payroll_month', 'installment_amount', 'principal_paid', 'interest_paid', 'remaining_balance', 'salary_sheet_reference', 'payroll_batch_id', 'created_by'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('loan_repayments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
