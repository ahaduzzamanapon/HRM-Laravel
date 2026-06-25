<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class CareerPageController extends Controller
{
    public function index()
    {
        $siteSetting = SiteSetting::first();
        if (!$siteSetting) {
            $siteSetting = new SiteSetting();
        }

        return view('admin.recruitment.career_page.index', compact('siteSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'career_title' => 'nullable|string|max:255',
            'career_subtitle' => 'nullable|string'
        ]);

        $siteSetting = SiteSetting::first();
        if (!$siteSetting) {
            $siteSetting = new SiteSetting();
        }

        $siteSetting->career_title = $request->career_title;
        $siteSetting->career_subtitle = $request->career_subtitle;
        $siteSetting->save();

        return redirect()->back()->with('success', 'Career page content updated successfully.');
    }
}
