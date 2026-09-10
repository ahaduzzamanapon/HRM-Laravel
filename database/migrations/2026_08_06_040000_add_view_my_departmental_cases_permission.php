<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use App\Models\RoleAndPermission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $parent = Permission::firstOrCreate(
            ['key' => 'disciplinary_actions'],
            ['name' => 'Disciplinary Actions']
        );

        $perm = Permission::firstOrCreate(
            ['key' => 'view_my_departmental_cases'],
            ['name' => 'View My Disciplinary Cases & Penalties', 'parent_id' => $parent->id]
        );

        // Attach permission to all roles so employees can view their own cases
        $roles = RoleAndPermission::all();
        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching([$perm->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $perm = Permission::where('key', 'view_my_departmental_cases')->first();
        if ($perm) {
            $perm->roles()->detach();
            $perm->delete();
        }
    }
};
