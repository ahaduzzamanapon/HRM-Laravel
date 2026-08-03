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
        Schema::table('loan_types', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_types', 'loan_code')) {
                $table->string('loan_code')->nullable();
            }
            if (!Schema::hasColumn('loan_types', 'interest_type')) {
                $table->string('interest_type')->default('Flat');
            }
            if (!Schema::hasColumn('loan_types', 'grace_period')) {
                $table->integer('grace_period')->default(0);
            }
            if (!Schema::hasColumn('loan_types', 'min_amount')) {
                $table->decimal('min_amount', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('loan_types', 'max_amount')) {
                $table->decimal('max_amount', 15, 2)->default(0);
            }
        });

        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'application_no')) {
                $table->string('application_no')->nullable();
            }
            if (!Schema::hasColumn('loans', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable();
            }
            if (!Schema::hasColumn('loans', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('loans', 'bank_account_info')) {
                $table->string('bank_account_info')->nullable();
            }
            if (!Schema::hasColumn('loans', 'voucher_no')) {
                $table->string('voucher_no')->nullable();
            }
        });

        if (!Schema::hasTable('loan_schedules')) {
            Schema::create('loan_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('loan_id');
                $table->integer('installment_no');
                $table->date('due_date');
                $table->decimal('principal_amount', 15, 2)->default(0);
                $table->decimal('interest_amount', 15, 2)->default(0);
                $table->decimal('total_installment', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->string('status')->default('Pending');
                $table->timestamps();

                $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('loan_approvals')) {
            Schema::create('loan_approvals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('loan_id');
                $table->unsignedInteger('approver_id');
                $table->string('level')->default('Manager');
                $table->string('status')->default('Approved');
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
                $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_approvals');
        Schema::dropIfExists('loan_schedules');
    }
};
