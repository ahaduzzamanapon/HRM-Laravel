<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\AttMachineDataSeeder;


class CronController extends Controller
{
    public function refreshDatabase()
    {
        set_time_limit(0); // unlimited execution time
        // $tables = [
        //     'users',
        //     'password_resets',
        //     'failed_jobs',
        //     'personal_access_tokens',
        //     'sitesettings',
        //     'ips',
        //     'permissions',
        //     'roles',
        //     'roll_has',
        //     'designations',
        //     'training_details',
        //     'job_experiences',
        //     'educational_qualifications',
        //     'nominee_information',
        //     'promotion_details',
        //     'salary_increments',
        //     'transfer_details',
        //     'personal_documents',
        //     'departments',
        //     'branchs',
        //     'holydays',
        //     'shifts',
        //     'shift_details',
        //     'att_machine_data',
        //     'leave_types',
        //     'leave_applications',
        //     'movements',
        //     'allowance_settings',
        //     'user_allowances',
        //     'attendance_time',
        //     'notices',
        //     'rewardings',
        //     'innovations',
        //     'employee_children_education_supports',
        //     'funeral_supports',
        //     'medical_supports',
        //     'penalties',
        //     'departmental_cases',
        //     'loan_types',
        //     'loans',
        //     'loan_repayments',
        //     'provident_fund_settings',
        //     'provident_fund_contributions',
        //     'payrolls',
        //     'salary_grades',
        //     'banksetups',
        //     'child_allowances',
        //     'taxsetups',
        // ];

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // foreach ($tables as $table) {
        //     DB::table($table)->truncate();
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Delete all images from storage folder
        // $imagePath = storage_path('app/public/user_images');
        // foreach (scandir($imagePath) as $file) {
        //     if (is_file($imagePath . '/' . $file)) {
        //         Storage::delete('public/user_images/' . $file);
        //     }
        // }
        if (!defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'r'));
        }


        $seeder = new DatabaseSeeder();
        $seeder->run();
        // $seeder = new AttMachineDataSeeder();
        // $seeder->run();



        return "Database refreshed successfully.";
    }
}