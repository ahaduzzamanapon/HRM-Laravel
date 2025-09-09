<?php

namespace App\Http\Controllers;

use App\Models\AllowanceSetting;
use Illuminate\Http\Request;

class AllowanceSettingController extends Controller
{
    public function index()
    {
        $allowanceSettings = AllowanceSetting::all();
        return view('allowance_settings.index', compact('allowanceSettings'));
    }

    public function create()
    {
        return view('allowance_settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:allowance_settings,name',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
            'tax_free_limit' => 'nullable|numeric',
            'city_specific' => 'boolean',
            'city_value' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        AllowanceSetting::create($request->all());

        return redirect()->route('allowanceSettings.index')
                         ->with('success', 'Allowance setting created successfully.');
    }

    public function show(AllowanceSetting $allowanceSetting)
    {
        return view('allowance_settings.show', compact('allowanceSetting'));
    }

    public function edit(AllowanceSetting $allowanceSetting)
    {
        return view('allowance_settings.edit', compact('allowanceSetting'));
    }

    public function update(Request $request, AllowanceSetting $allowanceSetting)
    {
        $request->validate([
            'name' => 'required|unique:allowance_settings,name,' . $allowanceSetting->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
            'tax_free_limit' => 'nullable|numeric',
            'city_specific' => 'boolean',
            'city_value' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $allowanceSetting->update($request->all());

        return redirect()->route('allowanceSettings.index')
                         ->with('success', 'Allowance setting updated successfully.');
    }

    public function destroy(AllowanceSetting $allowanceSetting)
    {
        $allowanceSetting->delete();

        return redirect()->route('allowanceSettings.index')
                         ->with('success', 'Allowance setting deleted successfully.');
    }
}
