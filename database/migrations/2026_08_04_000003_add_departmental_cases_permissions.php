<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use App\Models\RoleAndPermission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Get or create parent permission "Disciplinary Actions"
        $parent = Permission::firstOrCreate(
            ['key' => 'disciplinary_actions'],
            ['name' => 'Disciplinary Actions']
        );

        // 2. Define granular departmental case permissions
        $permissions = [
            ['key' => 'view_departmental_cases', 'name' => 'View Departmental Cases', 'parent_id' => $parent->id],
            ['key' => 'add_departmental_cases', 'name' => 'Add Departmental Case', 'parent_id' => $parent->id],
            ['key' => 'edit_departmental_cases', 'name' => 'Edit Departmental Case', 'parent_id' => $parent->id],
            ['key' => 'delete_departmental_cases', 'name' => 'Delete Departmental Case', 'parent_id' => $parent->id],
            ['key' => 'notify_departmental_cases', 'name' => 'Notify Employee Disciplinary Action', 'parent_id' => $parent->id],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            $createdPerm = Permission::firstOrCreate(
                ['key' => $perm['key']],
                ['name' => $perm['name'], 'parent_id' => $perm['parent_id']]
            );
            $permissionIds[] = $createdPerm->id;
        }

        // 3. Attach new permissions to Super Admin & Admin roles
        $roles = RoleAndPermission::whereIn('key', ['super_admin', 'Admin', 'HR'])->orWhereIn('name', ['Super Admin', 'Admin', 'HR'])->get();
        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $keys = [
            'view_departmental_cases',
            'add_departmental_cases',
            'edit_departmental_cases',
            'delete_departmental_cases',
            'notify_departmental_cases'
        ];
        Permission::whereIn('key', $keys)->delete();
    }
};
