<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pension_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('scheme_id')->constrained('pension_schemes')->onDelete('cascade');
            $table->date('retirement_date')->nullable();
            $table->string('retirement_type')->nullable(); // Superannuation, Voluntary, Medical, etc.
            $table->integer('qualifying_service_years')->nullable();
            $table->integer('qualifying_service_months')->nullable();
            $table->decimal('last_basic_pay', 15, 2)->nullable();
            $table->string('eligibility_status')->default('Pending'); // Eligible, Not Eligible, Pending Review
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pension_profiles');
    }
};
