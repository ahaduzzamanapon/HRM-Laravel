<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\RoleAndPermission;

class AddDashboardAndProfilePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Root Module: Dashboard & Profile
        $root = Permission::firstOrCreate(
            ['key' => 'dashboard_and_profile'],
            [
                'name' => 'Dashboard & Profile',
                'parent_id' => null,
            ]
        );

        // 2. Child 1: Dashboard
        $dashboard = Permission::firstOrCreate(
            ['key' => 'dashboard'],
            [
                'name' => 'Dashboard',
                'parent_id' => $root->id,
            ]
        );

        // 3. Child 2: My Profile
        $myProfile = Permission::firstOrCreate(
            ['key' => 'my_profile'],
            [
                'name' => 'My Profile',
                'parent_id' => $root->id,
            ]
        );

        // 4. Attach new permissions to all existing roles so no existing user loses access by default
        $permissionIds = [$root->id, $dashboard->id, $myProfile->id];
        $allRoles = RoleAndPermission::all();
        foreach ($allRoles as $role) {
            $role->permissions()->syncWithoutDetaching($permissionIds);
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
        $keys = ['dashboard_and_profile', 'dashboard', 'my_profile'];
        $permIds = Permission::whereIn('key', $keys)->pluck('id')->toArray();
        DB::table('roll_has')->whereIn('permission_id', $permIds)->delete();
        Permission::whereIn('key', $keys)->delete();
    }
}
