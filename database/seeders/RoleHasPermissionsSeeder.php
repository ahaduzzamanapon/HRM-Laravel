<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleAndPermission;
use App\Models\Permission;

class RoleHasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get roles
        $adminRole = RoleAndPermission::where('name', 'Admin')->first();
        $hrRole = RoleAndPermission::where('name', 'HR')->first();
        $employeeRole = RoleAndPermission::where('name', 'Employee')->first();

        // Get permissions
        $viewUsersPermission = Permission::where('name', 'view_users')->first();
        $addUsersPermission = Permission::where('name', 'add_users')->first();
        $editUsersPermission = Permission::where('name', 'edit_users')->first();
        $deleteUsersPermission = Permission::where('name', 'delete_users')->first();
        $manageRolesPermission = Permission::where('name', 'manage_roles')->first();
        $managePermissionsPermission = Permission::where('name', 'manage_permissions')->first();
        $userManagementPermission = Permission::where('name', 'user_management')->first();
        $userPermission = Permission::where('name', 'user')->first();
        $rollAndPermissionPermission = Permission::where('name', 'roll_and_permission')->first();
        $settingsPermission = Permission::where('name', 'settings')->first();
        $siteSettingsPermission = Permission::where('name', 'site_settings')->first();
        $designationsPermission = Permission::where('name', 'designations')->first();


        // Assign permissions to Admin role
        $adminRole->permissions()->attach([
            $viewUsersPermission->id,
            $addUsersPermission->id,
            $editUsersPermission->id,
            $deleteUsersPermission->id,
            $manageRolesPermission->id,
            $managePermissionsPermission->id,
            $userManagementPermission->id,
            $userPermission->id,
            $rollAndPermissionPermission->id,
            $settingsPermission->id,
            $siteSettingsPermission->id,
            $designationsPermission->id,
        ]);

        // Assign permissions to HR role
        $hrRole->permissions()->attach([
            $viewUsersPermission->id,
            $addUsersPermission->id,
            $editUsersPermission->id,
            $userManagementPermission->id,
            $userPermission->id,
            $settingsPermission->id,
            $siteSettingsPermission->id,
            $designationsPermission->id,
        ]);

        // Assign permissions to Employee role
        $employeeRole->permissions()->attach([
            $viewUsersPermission->id,
        ]);
    }
}
