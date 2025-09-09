<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission; // Assuming Permission model handles permissions

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing permissions to avoid duplicates on re-seeding
        Permission::truncate();

        // Main Categories (Parents)
        $staffManagement = Permission::create(['name' => 'Staff Management', 'key' => 'staff_management']);
        $organization = Permission::create(['name' => 'Organization', 'key' => 'organization']);
        $hr = Permission::create(['name' => 'HR', 'key' => 'hr']);
        $settings = Permission::create(['name' => 'Settings', 'key' => 'settings']);

        // Staff Management Sub-permissions
        Permission::create(['name' => 'View Employees', 'key' => 'view_employees', 'parent_id' => $staffManagement->id]);
        Permission::create(['name' => 'Add Employee', 'key' => 'add_employee', 'parent_id' => $staffManagement->id]);
        Permission::create(['name' => 'Edit Employee', 'key' => 'edit_employee', 'parent_id' => $staffManagement->id]);
        Permission::create(['name' => 'Delete Employee', 'key' => 'delete_employee', 'parent_id' => $staffManagement->id]);

        // Organization Sub-permissions
        Permission::create(['name' => 'Manage Designations', 'key' => 'manage_designations', 'parent_id' => $organization->id]);
        Permission::create(['name' => 'Manage Departments', 'key' => 'manage_departments', 'parent_id' => $organization->id]);
        Permission::create(['name' => 'Manage Branches', 'key' => 'manage_branches', 'parent_id' => $organization->id]);

        // HR Sub-permissions
        Permission::create(['name' => 'Manage Holidays', 'key' => 'manage_holidays', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Manage Shifts', 'key' => 'manage_shifts', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Upload Attendance Files', 'key' => 'upload_attendance_files', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Manage Leave Types', 'key' => 'manage_leave_types', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Apply Leave', 'key' => 'apply_leave', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Approve Leave', 'key' => 'approve_leave', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Leave Applications', 'key' => 'leave_applications', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'Movements', 'key' => 'movements', 'parent_id' => $hr->id]);
        Permission::create(['name' => 'process_attendance', 'key' => 'process_attendance', 'parent_id' => $hr->id]);

        // Settings Sub-permissions
        Permission::create(['name' => 'Manage Site Settings', 'key' => 'manage_site_settings', 'parent_id' => $settings->id]);
        Permission::create(['name' => 'Manage Roles and Permissions', 'key' => 'manage_roles_and_permissions', 'parent_id' => $settings->id]);
        Permission::create(['name' => 'Manage Allowance Settings', 'key' => 'manage_allowance_settings', 'parent_id' => $settings->id]);
    }
}
