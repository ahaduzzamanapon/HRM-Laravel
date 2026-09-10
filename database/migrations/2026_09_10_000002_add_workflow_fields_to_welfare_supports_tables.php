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
                if (!Schema::hasColumn($tableName, 'status')) {
                    $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Disbursed'])->default('Approved')->after('remarks');
                }
                if (!Schema::hasColumn($tableName, 'attachment')) {
                    $table->string('attachment')->nullable()->after('status');
                }
                if (!Schema::hasColumn($tableName, 'approved_by')) {
                    $table->integer('approved_by')->unsigned()->nullable()->after('attachment');
                    $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                }
                if (!Schema::hasColumn($tableName, 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn($tableName, 'admin_remarks')) {
                    $table->text('admin_remarks')->nullable()->after('approved_at');
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
                if (Schema::hasColumn($tableName, 'approved_by')) {
                    $table->dropForeign([$tableName . '_approved_by_foreign']);
                }
                $table->dropColumn(['status', 'attachment', 'approved_by', 'approved_at', 'admin_remarks']);
            });
        }
    }
};
