<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provident_fund_loans', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('installments');
            $table->decimal('monthly_installment', 10, 2);
            $table->date('disbursement_date')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->decimal('outstanding_balance', 10, 2);
            $table->enum('status', ['Pending', 'Approved', 'Disbursed', 'Repaid', 'Rejected'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provident_fund_loans');
    }
};
