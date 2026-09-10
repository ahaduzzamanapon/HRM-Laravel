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
        $tables = ['medical_supports', 'funeral_supports', 'employee_children_education_supports'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $afterCol = Schema::hasColumn($tableName, 'amount') ? 'amount' : (Schema::hasColumn($tableName, 'financial_assistance') ? 'financial_assistance' : 'employee_id');

                if (!Schema::hasColumn($tableName, 'requested_amount')) {
                    $table->decimal('requested_amount', 10, 2)->nullable()->after($afterCol);
                }
                if (!Schema::hasColumn($tableName, 'approved_amount')) {
                    $table->decimal('approved_amount', 10, 2)->nullable()->after('requested_amount');
                }
                if (!Schema::hasColumn($tableName, 'disbursed_amount')) {
                    $table->decimal('disbursed_amount', 10, 2)->nullable()->after('approved_amount');
                }
                if (!Schema::hasColumn($tableName, 'disbursed_by')) {
                    $table->integer('disbursed_by')->unsigned()->nullable();
                }
                if (!Schema::hasColumn($tableName, 'disbursed_at')) {
                    $table->timestamp('disbursed_at')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'disbursement_reference')) {
                    $table->string('disbursement_reference')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable();
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
        $tables = ['medical_supports', 'funeral_supports', 'employee_children_education_supports'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropColumn([
                    'requested_amount',
                    'approved_amount',
                    'disbursed_amount',
                    'disbursed_by',
                    'disbursed_at',
                    'disbursement_reference',
                    'rejection_reason'
                ]);
            });
        }
    }
};
