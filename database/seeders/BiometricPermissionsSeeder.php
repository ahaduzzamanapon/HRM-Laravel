<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RoleAndPermission;

class BiometricPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $biometric = Permission::firstOrCreate(
            ['key' => 'biometric'],
            ['name' => 'Biometric Management']
        );

        $subPermissions = [
            ['key' => 'manage_biometric_devices', 'name' => 'Biometric Devices'],
            ['key' => 'view_biometric_attendance_logs', 'name' => 'Biometric Attendance Logs'],
            ['key' => 'manage_biometric_employee_mappings', 'name' => 'Biometric Employee Mappings'],
            ['key' => 'manage_biometric_commands', 'name' => 'Biometric Commands'],
        ];

        $createdIds = [$biometric->id];

        foreach ($subPermissions as $perm) {
            $p = Permission::firstOrCreate(
                ['key' => $perm['key']],
                ['name' => $perm['name'], 'parent_id' => $biometric->id]
            );
            $createdIds[] = $p->id;
        }

        // Attach created permissions to Admin and HR roles
        $roles = RoleAndPermission::all();
        foreach ($roles as $role) {
            // Attach to Admin, HR, Super Admin or any admin role
            if (in_array(strtolower($role->name), ['admin', 'super admin', 'super administrator', 'hr'])) {
                $role->permissions()->syncWithoutDetaching($createdIds);
            }
        }
    }
}
