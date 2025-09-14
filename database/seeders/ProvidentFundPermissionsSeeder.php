<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ProvidentFundPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'provident_fund', 'key' => 'provident_fund']);
        Permission::create(['name' => 'manage_provident_fund_settings', 'key' => 'manage_provident_fund_settings']);
        Permission::create(['name' => 'view_provident_fund_statements', 'key' => 'view_provident_fund_statements']);
    }
}