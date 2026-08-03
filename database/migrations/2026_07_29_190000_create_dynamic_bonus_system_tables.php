<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDynamicBonusSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create bonus_settings table
        if (!Schema::hasTable('bonus_settings')) {
            Schema::create('bonus_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('branch_id')->nullable(); // null means all branches
                $table->string('title');
                $table->enum('bonus_type', ['percentage', 'fixed'])->default('percentage');
                $table->enum('calculation_base', ['basic_salary', 'gross_salary', 'fixed'])->default('basic_salary');
                $table->decimal('amount_percentage', 10, 2)->default(0.00); // % or fixed amount
                $table->date('bonus_month'); // YYYY-MM-01
                $table->string('religion')->nullable(); // Islam, Hinduism, Christianity, Buddhism, or null (All)
                $table->integer('min_service_months')->default(0); // minimum service required in months
                $table->enum('status', ['active', 'processed', 'inactive'])->default('active');
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('cascade');
            });
        }

        // 2. Create employee_bonuses table (Disbursements)
        if (!Schema::hasTable('employee_bonuses')) {
            Schema::create('employee_bonuses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bonus_setting_id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('branch_id')->nullable();
                $table->date('bonus_month');
                $table->decimal('base_amount', 12, 2)->default(0.00); // basic or gross at time of calculation
                $table->decimal('bonus_amount', 12, 2)->default(0.00);
                $table->enum('payment_status', ['pending', 'approved', 'paid'])->default('pending');
                $table->date('payment_date')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('bonus_setting_id')->references('id')->on('bonus_settings')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('set null');
            });
        }

        // 3. Insert 'manage_bonuses' permission if it doesn't exist
        $exists = DB::table('permissions')->where('key', 'manage_bonuses')->exists();
        if (!$exists) {
            DB::table('permissions')->insert([
                'name' => 'Manage Bonuses',
                'key' => 'manage_bonuses',
                'cat_id' => 'payroll',
                'parent_id' => null,
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
        Schema::dropIfExists('employee_bonuses');
        Schema::dropIfExists('bonus_settings');
        DB::table('permissions')->where('key', 'manage_bonuses')->delete();
    }
}
