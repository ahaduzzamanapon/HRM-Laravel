<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pf_settlements', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->integer('branch_id')->unsigned()->nullable();
            $table->decimal('total_balance', 15, 2);
            $table->decimal('settlement_amount', 15, 2);
            $table->string('reason')->nullable(); // Resignation, Retirement, etc.
            $table->enum('status', ['Pending', 'HR Approved', 'Finance Approved', 'Trustee Approved', 'Settled', 'Rejected'])->default('Pending');
            $table->date('settlement_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pf_settlements');
    }
};
