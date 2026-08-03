<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add branch_id to notices, rewardings, innovations, penalties, departmental_cases,
     * loan_types tables to enable per-branch data isolation.
     */
    public function up()
    {
        // Notices — broadcast to specific branch or all (null = system-wide)
        if (!Schema::hasColumn('notices', 'branch_id')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->unsignedInteger('branch_id')->nullable()->after('id');
                $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('set null');
            });
        }

        // Innovations — linked to an employee, scoped via user; also direct branch_id for convenience
        if (!Schema::hasColumn('innovations', 'branch_id')) {
            Schema::table('innovations', function (Blueprint $table) {
                $table->unsignedInteger('branch_id')->nullable()->after('id');
            });
        }

        // Penalties — linked to employee
        if (!Schema::hasColumn('penalties', 'branch_id')) {
            Schema::table('penalties', function (Blueprint $table) {
                $table->unsignedInteger('branch_id')->nullable()->after('id');
            });
        }
    }

    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('innovations', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });

        Schema::table('penalties', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
    }
};
