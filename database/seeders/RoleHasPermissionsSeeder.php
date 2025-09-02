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
        // Clear existing role-permission attachments
        \DB::table('roll_has')->truncate();

        // Get roles
        $adminRole = RoleAndPermission::where('name', 'Admin')->first();
        $hrRole = RoleAndPermission::where('name', 'HR')->first();
        $employeeRole = RoleAndPermission::where('name', 'Employee')->first();

        // Get all permissions
        $allPermissions = Permission::all();

        // Assign all permissions to Admin role
        if ($adminRole) {
            $adminRole->permissions()->attach($allPermissions->pluck('id')->toArray());
        }

        // Assign specific permissions to HR role
        if ($hrRole) {
            $hrPermissions = [
                'staff_management', 'view_employees', 'add_employee', 'edit_employee',
                'organization', 'manage_designations', 'manage_departments', 'manage_branches',
                'hr', 'manage_holidays', 'manage_shifts', 'upload_attendance_files', 'manage_leave_types', 'apply_leave', 'approve_leave', 'leave_applications', // Added
                'settings', 'manage_site_settings', 'manage_roles_and_permissions'
            ];
            $hrRole->permissions()->attach(Permission::whereIn('key', $hrPermissions)->pluck('id')->toArray());
        }

        // Assign specific permissions to Employee role
        if ($employeeRole) {
            $employeePermissions = [
                'staff_management', 'view_employees', 'apply_leave', 'leave_applications' // Added
            ];
            $employeeRole->permissions()->attach(Permission::whereIn('key', $employeePermissions)->pluck('id')->toArray());
        }
    }
}
