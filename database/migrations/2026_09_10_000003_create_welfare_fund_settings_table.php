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
        if (!Schema::hasTable('welfare_fund_settings')) {
            Schema::create('welfare_fund_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('welfare_fund_enabled')->default(true);
                $table->enum('deduction_type', ['fixed', 'percentage'])->default('fixed');
                $table->enum('deduction_policy', ['compulsory', 'optional'])->default('compulsory');
                $table->boolean('company_contribution_enabled')->default(false);
                $table->enum('company_contribution_type', ['fixed', 'matching_percentage'])->default('matching_percentage');
                $table->decimal('company_contribution_amount', 10, 2)->default(0.00);
                $table->decimal('max_medical_limit', 10, 2)->default(50000.00);
                $table->decimal('max_funeral_limit', 10, 2)->default(30000.00);
                $table->decimal('max_education_limit', 10, 2)->default(25000.00);
                $table->boolean('allow_negative_balance')->default(false);
                $table->boolean('require_attachment')->default(true);
                $table->timestamps();
            });

            // Insert default row
            DB::table('welfare_fund_settings')->insert([
                'welfare_fund_enabled' => true,
                'deduction_type' => 'fixed',
                'deduction_policy' => 'compulsory',
                'company_contribution_enabled' => false,
                'company_contribution_type' => 'matching_percentage',
                'company_contribution_amount' => 100.00,
                'max_medical_limit' => 50000.00,
                'max_funeral_limit' => 30000.00,
                'max_education_limit' => 25000.00,
                'allow_negative_balance' => false,
                'require_attachment' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_fund_settings');
    }
};
