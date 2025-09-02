<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteSetting::create([
            'site_name' => 'HRM System',
            'site_email' => 'info@hrm.com',
            'site_phone' => '1234567890',
            'site_address' => '123 Main St, City, Country',
            'site_logo' => 'logo.png',
            'site_favicon' => 'favicon.ico',
            'site_description' => 'Human Resource Management System',
            'site_keywords' => 'HRM, Human Resource, Management',
            'site_author' => 'Gemini',
            'site_footer' => 'HRM System © 2025',
        ]);
    }
}
