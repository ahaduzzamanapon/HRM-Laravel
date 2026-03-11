<?php

namespace App\Http\Controllers\Api;

use App\Models\ProvidentFundSetting;
use Illuminate\Http\Request;

class ProvidentFundSettingApiController extends BaseApiController
{
    public function index()
    {
        $settings = ProvidentFundSetting::first();
        return $this->successResponse($settings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_percentage' => 'required|numeric|min:0|max:100',
            'employer_percentage' => 'required|numeric|min:0|max:100',
        ]);
        $settings = ProvidentFundSetting::updateOrCreate(['id' => 1], $validated);
        return $this->successResponse($settings, 'PF settings saved successfully');
    }
}
