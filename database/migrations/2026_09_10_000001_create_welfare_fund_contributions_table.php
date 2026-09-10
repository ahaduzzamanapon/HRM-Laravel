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
        if (!Schema::hasTable('welfare_fund_contributions')) {
            Schema::create('welfare_fund_contributions', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedBigInteger('payroll_id')->nullable();
                $table->enum('contribution_type', ['employee', 'company', 'adjustment', 'reversal'])->default('employee');
                $table->decimal('amount', 10, 2);
                $table->string('month', 20)->nullable();
                $table->string('year', 10)->nullable();
                $table->date('contribution_date');
                $table->text('remarks')->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->timestamps();

                $table->index(['user_id', 'payroll_id', 'month', 'year', 'contribution_type'], 'wfc_unique_idx');
            });
        } else {
            Schema::table('welfare_fund_contributions', function (Blueprint $table) {
                if (!Schema::hasColumn('welfare_fund_contributions', 'contribution_type')) {
                    $table->enum('contribution_type', ['employee', 'company', 'adjustment', 'reversal'])->default('employee')->after('payroll_id');
                }
                if (!Schema::hasColumn('welfare_fund_contributions', 'created_by')) {
                    $table->integer('created_by')->unsigned()->nullable()->after('remarks');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_fund_contributions');
    }
};
