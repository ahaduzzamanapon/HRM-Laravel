<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\RoleAndPermission;

class AddDashboardWidgetsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dashboardParent = Permission::where('key', 'dashboard')->first();
        if (!$dashboardParent) {
            return;
        }

        $widgetPermissions = [
            ['key' => 'dashboard_daily_attendance', 'name' => 'Daily Attendance Widget'],
            ['key' => 'dashboard_monthly_attendance', 'name' => 'Monthly Summary Widget'],
            ['key' => 'dashboard_organization_info', 'name' => 'Organization Info Widget'],
            ['key' => 'dashboard_leave_info', 'name' => 'Leave Info Widget'],
            ['key' => 'dashboard_payroll_info', 'name' => 'Payroll Info Widget'],
            ['key' => 'dashboard_provident_fund_info', 'name' => 'Allowance & PF Widget'],
            ['key' => 'dashboard_new_join_info', 'name' => 'New Join Employees Widget'],
        ];

        $createdIds = [];
        foreach ($widgetPermissions as $item) {
            $perm = Permission::firstOrCreate(
                ['key' => $item['key']],
                [
                    'name' => $item['name'],
                    'parent_id' => $dashboardParent->id
                ]
            );
            $createdIds[] = $perm->id;
        }

        $allRoles = RoleAndPermission::all();
        foreach ($allRoles as $role) {
            $role->permissions()->syncWithoutDetaching($createdIds);
        }

        \App\Services\AuthorizationEngine::clearCache();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $keys = [
            'dashboard_daily_attendance',
            'dashboard_monthly_attendance',
            'dashboard_organization_info',
            'dashboard_leave_info',
            'dashboard_payroll_info',
            'dashboard_provident_fund_info',
            'dashboard_new_join_info',
        ];
        $permIds = Permission::whereIn('key', $keys)->pluck('id')->toArray();
        DB::table('roll_has')->whereIn('permission_id', $permIds)->delete();
        Permission::whereIn('key', $keys)->delete();
    }
}
