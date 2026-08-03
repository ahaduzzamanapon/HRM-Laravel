<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('provident_fund_contributions', function (Blueprint $table) {
            $table->integer('branch_id')->unsigned()->nullable()->after('employee_id');
            $table->unsignedBigInteger('scheme_id')->nullable()->after('branch_id');
            $table->decimal('voluntary_contribution', 15, 2)->default(0)->after('employer_contribution');
            $table->decimal('profit_amount', 15, 2)->default(0)->after('voluntary_contribution');
            $table->enum('status', ['Pending', 'Verified', 'Processed'])->default('Pending')->after('profit_amount');
            $table->text('adjustment_note')->nullable()->after('status');

            $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('pf_schemes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('provident_fund_contributions', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['scheme_id']);
            $table->dropColumn(['branch_id', 'scheme_id', 'voluntary_contribution', 'profit_amount', 'status', 'adjustment_note']);
        });
    }
};
