<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pf_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->integer('branch_id')->unsigned()->nullable();
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->enum('transaction_type', ['contribution', 'withdrawal', 'loan_disbursement', 'loan_repayment', 'profit', 'settlement', 'correction']);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable(); // Class name of the related model
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the related model
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('pf_schemes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pf_ledgers');
    }
};
