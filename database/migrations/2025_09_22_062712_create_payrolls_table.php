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
            $table->integer('emp_type');
            $table->string('dept_id')->nullable();
            $table->string('desig_id')->nullable();
            $table->integer('emp_status');
            $table->date('salary_month')->nullable();
            $table->integer('n_days')->nullable();
            $table->integer('present')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('leaves')->nullable();
            $table->integer('weekend')->nullable();
            $table->integer('holiday')->nullable();

            $table->decimal('pay_day', 6, 2)->nullable();
            $table->integer('grade');
            $table->decimal('b_salary', 10, 2)->nullable();
            $table->decimal('g_salary', 10, 2)->nullable();

            $table->decimal('pay_salary', 10, 2)->nullable();

            $table->decimal('h_rent', 10, 2)->nullable();
            $table->decimal('m_allow', 10, 2)->nullable();
            $table->decimal('f_allow', 10, 2)->nullable();
            $table->decimal('special_allow', 10, 2)->nullable();
            $table->decimal('child_allow', 10, 2)->nullable();
            $table->decimal('trans_allow', 10, 2)->nullable();
            $table->decimal('pf_allow_bank', 10, 2)->nullable();

            $table->decimal('total_allow', 10, 2)->nullable();
            $table->string('all_allows')->nullable();

            $table->decimal('gross_salary', 10, 2)->nullable();

            $table->decimal('absent_deduct', 10, 2)->nullable();
            $table->decimal('pf_deduct', 10, 2)->nullable();
            $table->decimal('tax_deduct', 10, 2)->nullable();
            $table->decimal('bene_deduct', 10, 2)->nullable();
            $table->decimal('auto_mobile_d', 10, 2)->nullable();
            $table->decimal('h_loan_deduct', 10, 2)->nullable();
            $table->decimal('p_loan_deduct', 10, 2)->nullable();
            $table->decimal('stump_deduct', 10, 2)->nullable();
            $table->decimal('others_deduct', 10, 2)->nullable();
            $table->decimal('total_deduct', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2)->nullable();
            $table->timestamps();
            $table->integer('updated_by');
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
