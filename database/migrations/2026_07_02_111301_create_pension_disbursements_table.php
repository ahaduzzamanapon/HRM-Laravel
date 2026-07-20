<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pension_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('pension_profiles')->onDelete('cascade');
            $table->string('disbursement_month'); // e.g. July 2026
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('arrear_amount', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('net_payable', 15, 2)->default(0);
            $table->string('payment_method')->default('EFT'); // EFT, BEFTN, Cheque
            $table->string('status')->default('Pending'); // Pending, Processing, Paid, Failed
            $table->string('bank_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pension_disbursements');
    }
};
