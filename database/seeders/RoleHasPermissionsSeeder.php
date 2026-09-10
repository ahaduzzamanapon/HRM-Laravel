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
                'hr', 'manage_holidays', 'manage_shifts', 'upload_attendance_files', 'manage_leave_types', 'apply_leave', 'approve_leave', 'leave_applications', 'movements', // Added
                'settings', 'manage_site_settings', 'manage_roles_and_permissions'
            ];
            $hrRole->permissions()->attach(Permission::whereIn('key', $hrPermissions)->pluck('id')->toArray());
        }

        // Assign specific permissions to Employee roles
        $employeeRoles = RoleAndPermission::where('name', 'Employee')->orWhere('key', 'employee')->get();
        if ($employeeRoles->count() > 0) {
            $employeePermissions = [
                'staff_management', 'view_employees', 'apply_leave', 'leave_applications', 'movements',
                'loans_and_advances', 'apply_loans', 'edit_loans', 'manage_loans',
                'disciplinary_actions', 'view_my_departmental_cases'
            ];
            $permIds = Permission::whereIn('key', $employeePermissions)->pluck('id')->toArray();
            foreach ($employeeRoles as $empRole) {
                $empRole->permissions()->syncWithoutDetaching($permIds);
            }
        }
    }
}
