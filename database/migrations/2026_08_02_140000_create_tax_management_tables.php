<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tax_fiscal_years')) {
            Schema::create('tax_fiscal_years', function (Blueprint $table) {
                $table->id();
                $table->string('year_name');
                $table->date('start_date');
                $table->date('end_date');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tax_slabs')) {
            Schema::create('tax_slabs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fiscal_year_id')->nullable();
                $table->string('gender_category')->default('All');
                $table->decimal('min_income', 15, 2)->default(0);
                $table->decimal('max_income', 15, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(0);
                $table->decimal('fixed_amount', 15, 2)->default(0);
                $table->integer('slab_order')->default(1);
                $table->string('status')->default('Active');
                $table->timestamps();

                $table->foreign('fiscal_year_id')->references('id')->on('tax_fiscal_years')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('employee_tax_profiles')) {
            Schema::create('employee_tax_profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->string('tin_number')->nullable();
                $table->string('tax_circle')->nullable();
                $table->string('tax_zone')->nullable();
                $table->string('tax_region')->nullable();
                $table->string('filing_status')->default('Registered');
                $table->decimal('investment_amount', 15, 2)->default(0);
                $table->decimal('rebate_claimed', 15, 2)->default(0);
                $table->decimal('yearly_tax_estimate', 15, 2)->default(0);
                $table->decimal('monthly_tax_deduction', 15, 2)->default(0);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tax_adjustments')) {
            Schema::create('tax_adjustments', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedBigInteger('fiscal_year_id')->nullable();
                $table->string('adjustment_type')->default('Manual_Adjustment');
                $table->decimal('amount', 15, 2)->default(0);
                $table->text('reason')->nullable();
                $table->unsignedInteger('processed_by')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_adjustments');
        Schema::dropIfExists('employee_tax_profiles');
        Schema::dropIfExists('tax_slabs');
        Schema::dropIfExists('tax_fiscal_years');
    }
};
