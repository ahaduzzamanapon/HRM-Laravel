<?php

namespace App\Http\Controllers\Api;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingApiController extends BaseApiController
{
    public function index()
    {
        $settings = SiteSetting::first();
        return $this->successResponse($settings);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:5',
        ]);

        $settings = SiteSetting::updateOrCreate(['id' => 1], $validated);
        return $this->successResponse($settings, 'Site settings updated successfully');
    }
}
