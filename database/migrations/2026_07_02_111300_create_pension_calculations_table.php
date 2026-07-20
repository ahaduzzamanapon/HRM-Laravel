<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pension_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('pension_profiles')->onDelete('cascade');
            $table->decimal('gross_pension', 15, 2)->default(0);
            $table->decimal('commuted_amount', 15, 2)->default(0);
            $table->decimal('gratuity', 15, 2)->default(0);
            $table->decimal('medical_allowance', 15, 2)->default(0);
            $table->decimal('monthly_pension', 15, 2)->default(0);
            $table->decimal('net_pension', 15, 2)->default(0);
            $table->string('status')->default('Draft'); // Draft, Approved, Finalized
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pension_calculations');
    }
};
