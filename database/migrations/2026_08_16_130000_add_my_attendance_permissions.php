<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\RoleAndPermission;
use App\Models\RollHas;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure root module parent_id = 140 (Dashboard & Profile) or create
        $rootModule = Permission::find(140);
        $parentId = $rootModule ? $rootModule->id : null;

        // 2. Ensure view_my_attendance and my_attendance exist
        $permViewMy = Permission::firstOrCreate(
            ['key' => 'view_my_attendance'],
            [
                'name' => 'My Attendance Report',
                'parent_id' => $parentId
            ]
        );

        $permMyAttn = Permission::firstOrCreate(
            ['key' => 'my_attendance'],
            [
                'name' => 'My Attendance',
                'parent_id' => $parentId
            ]
        );

        // 3. Assign permissions to all roles so employees have access immediately
        $allRoles = RoleAndPermission::all();
        $targetPermIds = array_unique([$permViewMy->id, $permMyAttn->id]);

        foreach ($allRoles as $role) {
            foreach ($targetPermIds as $permId) {
                RollHas::firstOrCreate([
                    'roll_id' => $role->id,
                    'permission_id' => $permId
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permIds = Permission::whereIn('key', ['view_my_attendance', 'my_attendance'])->pluck('id');
        RollHas::whereIn('permission_id', $permIds)->delete();
        Permission::whereIn('key', ['view_my_attendance', 'my_attendance'])->delete();
    }
};
