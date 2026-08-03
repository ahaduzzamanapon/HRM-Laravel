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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pf_account_number')) {
                $table->string('pf_account_number')->nullable()->after('is_pf_member');
            }
        });

        Schema::table('pf_withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('pf_withdrawals', 'type')) {
                $table->string('type')->default('Full')->after('amount');
            }
            if (!Schema::hasColumn('pf_withdrawals', 'approved_amount')) {
                $table->decimal('approved_amount', 15, 2)->nullable()->after('amount');
            }
        });

        Schema::table('provident_fund_loans', function (Blueprint $table) {
            if (!Schema::hasColumn('provident_fund_loans', 'approved_amount')) {
                $table->decimal('approved_amount', 15, 2)->nullable()->after('amount');
            }
        });

        Schema::dropIfExists('pf_yearly_interests');
        Schema::create('pf_yearly_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->year('year');
            $table->decimal('balance_before', 15, 2)->default(0);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pf_yearly_interests');

        Schema::table('provident_fund_loans', function (Blueprint $table) {
            $table->dropColumn('approved_amount');
        });

        Schema::table('pf_withdrawals', function (Blueprint $table) {
            $table->dropColumn(['type', 'approved_amount']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pf_account_number');
        });
    }
};
