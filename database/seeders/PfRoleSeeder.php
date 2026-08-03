<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RoleAndPermission;
use Illuminate\Support\Facades\DB;

class PfRoleSeeder extends Seeder
{
    public function run()
    {
        // Define PF Permissions
        $permissions = [
            ['name' => 'View PF Dashboard', 'key' => 'view_pf_dashboard'],
            ['name' => 'Manage PF Schemes', 'key' => 'manage_pf_schemes'],
            ['name' => 'Process PF Contributions', 'key' => 'process_pf_contributions'],
            ['name' => 'Approve PF Loans', 'key' => 'approve_pf_loans'],
            ['name' => 'View All Branches PF', 'key' => 'view_all_branches_pf'],
            ['name' => 'Manage PF Withdrawals', 'key' => 'manage_pf_withdrawals'],
            ['name' => 'Manage PF Settlements', 'key' => 'manage_pf_settlements'],
            ['name' => 'View PF Reports', 'key' => 'view_pf_reports'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['key' => $perm['key']], ['name' => $perm['name']]);
        }

        // Example: Assigning these to Super Administrator (assuming role ID 1)
        $superAdmin = RoleAndPermission::where('name', 'Super Administrator')->first();
        if ($superAdmin) {
            $pfPermissionIds = Permission::whereIn('key', array_column($permissions, 'key'))->pluck('id')->toArray();
            $superAdmin->permissions()->syncWithoutDetaching($pfPermissionIds);
        }
    }
}
