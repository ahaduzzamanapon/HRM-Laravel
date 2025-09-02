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
        // Create permissions
        Permission::create(['name' => 'view_users', 'key' => 'view_users']);
        Permission::create(['name' => 'add_users', 'key' => 'add_users']);
        Permission::create(['name' => 'edit_users', 'key' => 'edit_users']);
        Permission::create(['name' => 'delete_users', 'key' => 'delete_users']);
        Permission::create(['name' => 'manage_roles', 'key' => 'manage_roles']);
        Permission::create(['name' => 'manage_permissions', 'key' => 'manage_permissions']);
        Permission::create(['name' => 'user_management', 'key' => 'user_management']); // For the sidebar menu
        Permission::create(['name' => 'user', 'key' => 'user']); // For the Users sub-menu
        Permission::create(['name' => 'roll_and_permission', 'key' => 'roll_and_permission']); // For the Role Management sub-menu
        Permission::create(['name' => 'settings', 'key' => 'settings']); // For the Settings menu
        Permission::create(['name' => 'site_settings', 'key' => 'site_settings']); // For the Site Settings sub-menu
        Permission::create(['name' => 'designations', 'key' => 'designations']); // For the Designations sub-menu
    }
}
