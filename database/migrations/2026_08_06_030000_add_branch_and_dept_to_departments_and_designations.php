<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('name');
            }
        });

        Schema::table('designations', function (Blueprint $table) {
            if (!Schema::hasColumn('designations', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('desi_name');
            }
            if (!Schema::hasColumn('designations', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('branch_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'branch_id')) {
                $table->dropColumn('branch_id');
            }
        });

        Schema::table('designations', function (Blueprint $table) {
            $columns = ['branch_id', 'department_id'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('designations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
