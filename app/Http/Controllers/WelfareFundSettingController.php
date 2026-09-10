<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WelfareFundSetting;
use App\Services\AuthorizationEngine;
use App\Services\WelfareFundService;
use Illuminate\Support\Facades\Auth;
use Flash;

class WelfareFundSettingController extends Controller
{
    public function edit()
    {
        if (!AuthorizationEngine::isSuperAdmin(Auth::user()) && !can('manage_welfare_settings')) {
            Flash::error('Only Super Admin / Management can configure Welfare Fund settings.');
            return redirect()->route('welfare.myStatement');
        }

        $settings = WelfareFundSetting::instance();
        return view('welfare_fund.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!AuthorizationEngine::isSuperAdmin(Auth::user()) && !can('manage_welfare_settings')) {
            Flash::error('Only Super Admin / Management can configure Welfare Fund settings.');
            return redirect()->route('welfare.myStatement');
        }

        $settings = WelfareFundSetting::instance();

        $validated = $request->validate([
            'welfare_fund_enabled' => 'nullable|boolean',
            'deduction_type' => 'required|in:fixed,percentage',
            'deduction_policy' => 'required|in:compulsory,optional',
            'company_contribution_enabled' => 'nullable|boolean',
            'company_contribution_type' => 'required|in:fixed,matching_percentage',
            'company_contribution_amount' => 'required|numeric|min:0',
            'max_medical_limit' => 'required|numeric|min:0',
            'max_funeral_limit' => 'required|numeric|min:0',
            'max_education_limit' => 'required|numeric|min:0',
            'allow_negative_balance' => 'nullable|boolean',
            'require_attachment' => 'nullable|boolean',
        ]);

        $validated['welfare_fund_enabled'] = $request->has('welfare_fund_enabled');
        $validated['company_contribution_enabled'] = $request->has('company_contribution_enabled');
        $validated['allow_negative_balance'] = $request->has('allow_negative_balance');
        $validated['require_attachment'] = $request->has('require_attachment');

        $settings->update($validated);

        WelfareFundService::audit('update_settings', null, null, null, null, 'Updated Welfare Fund Settings Configuration');

        Flash::success('Welfare Fund settings updated successfully.');
        return redirect()->route('welfare.settings.edit');
    }
}
