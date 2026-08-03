<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pf_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->integer('branch_id')->unsigned()->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['Pending', 'HR Approved', 'Finance Approved', 'Trustee Approved', 'Disbursed', 'Rejected'])->default('Pending');
            $table->date('disbursement_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pf_withdrawals');
    }
};
