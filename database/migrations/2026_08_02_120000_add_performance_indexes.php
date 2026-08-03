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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'branch_id') && Schema::hasColumn('users', 'status')) {
                $table->index(['branch_id', 'status'], 'idx_users_branch_status');
            }
            if (Schema::hasColumn('users', 'group_id')) {
                $table->index('group_id', 'idx_users_group_id');
            }
        });

        Schema::table('roles_and_permissions', function (Blueprint $table) {
            if (Schema::hasColumn('roles_and_permissions', 'branch_id')) {
                $table->index('branch_id', 'idx_roles_branch_id');
            }
        });

        Schema::table('roll_has_permissions', function (Blueprint $table) {
            if (Schema::hasColumn('roll_has_permissions', 'roll_id') && Schema::hasColumn('roll_has_permissions', 'permission_id')) {
                $table->index(['roll_id', 'permission_id'], 'idx_roll_has_roll_perm');
            }
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            if (Schema::hasColumn('leave_applications', 'user_id') && Schema::hasColumn('leave_applications', 'status')) {
                $table->index(['user_id', 'status'], 'idx_leave_app_user_status');
            }
            if (Schema::hasColumn('leave_applications', 'leave_type_id')) {
                $table->index('leave_type_id', 'idx_leave_app_type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_branch_status');
            $table->dropIndex('idx_users_group_id');
        });

        Schema::table('roles_and_permissions', function (Blueprint $table) {
            $table->dropIndex('idx_roles_branch_id');
        });

        Schema::table('roll_has_permissions', function (Blueprint $table) {
            $table->dropIndex('idx_roll_has_roll_perm');
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropIndex('idx_leave_app_user_status');
            $table->dropIndex('idx_leave_app_type_id');
        });
    }
};
