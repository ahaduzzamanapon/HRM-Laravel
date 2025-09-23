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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('branch_id');
            $table->integer('emp_status');
            $table->string('dept_id')->nullable();
            $table->string('desig_id')->nullable();
            $table->date('salary_month')->nullable();
            $table->integer('n_days')->nullable();
            $table->integer('present')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('leave')->nullable();
            $table->integer('weekend')->nullable();
            $table->integer('holiday')->nullable();

            $table->decimal('pay_day', 6, 2)->nullable();
            $table->decimal('b_salary', 10, 2)->nullable();
            $table->decimal('g_salary', 10, 2)->nullable();
            $table->decimal('pay_salary', 10, 2)->nullable();
            $table->decimal('total_allow', 10, 2)->nullable();
            $table->string('all_allows')->nullable();

            $table->decimal('absent_deduct', 10, 2)->nullable();
            $table->decimal('loan_deduct', 10, 2)->nullable();
            $table->decimal('pf_deduct', 10, 2)->nullable();
            $table->decimal('others_deduct', 10, 2)->nullable();
            $table->decimal('total_deduct', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2)->nullable();
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
        Schema::dropIfExists('payrolls');
    }
};
