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
        Schema::create('provident_fund_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('employee_contribution', 5, 2)->default(10.00);
            $table->decimal('employer_contribution', 5, 2)->default(10.00);
            $table->decimal('interest_rate', 5, 2)->default(8.50);
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
        Schema::dropIfExists('provident_fund_settings');
    }
};