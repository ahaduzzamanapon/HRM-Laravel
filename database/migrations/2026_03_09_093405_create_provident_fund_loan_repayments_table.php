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
        Schema::create('provident_fund_loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provident_fund_loan_id');
            $table->foreign('provident_fund_loan_id')->references('id')->on('provident_fund_loans')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('repayment_date');
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
        Schema::dropIfExists('provident_fund_loan_repayments');
    }
};
