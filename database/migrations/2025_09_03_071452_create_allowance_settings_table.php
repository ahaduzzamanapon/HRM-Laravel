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
        Schema::create('allowance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Basic Salary', 'HRA', 'Medical Allowance', 'Conveyance Allowance', 'Festival Bonus', 'Performance Bonus', 'Dearness Allowance', 'Special Allowance'
            $table->enum('type', ['percentage', 'fixed']); // 'percentage' or 'fixed'
            $table->decimal('value', 8, 2); // Percentage (e.g., 50.00 for 50%) or fixed amount
            $table->decimal('tax_free_limit', 10, 2)->nullable(); // For Medical Allowance, up to BDT 120,000 annually
            $table->boolean('city_specific')->default(false); // For HRA (Dhaka city)
            $table->decimal('city_value', 8, 2)->nullable(); // For HRA in Dhaka (e.g., 65.00 for 65%)
            $table->boolean('is_active')->default(true); // To enable/disable globally
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
        Schema::dropIfExists('allowance_settings');
    }
};
