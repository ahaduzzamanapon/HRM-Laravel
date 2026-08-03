<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PfScheme;

class PfSchemeController extends Controller
{
    public function index()
    {
        $schemes = PfScheme::all();
        return view('pf.schemes.index', compact('schemes'));
    }

    public function create()
    {
        return view('pf.schemes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_contribution_percentage' => 'required|numeric',
            'employer_contribution_percentage' => 'required|numeric',
        ]);

        PfScheme::create($request->all());

        return redirect()->route('pf.schemes.index')->with('success', 'PF Scheme created successfully.');
    }

    public function edit(PfScheme $scheme)
    {
        return view('pf.schemes.edit', compact('scheme'));
    }

    public function update(Request $request, PfScheme $scheme)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_contribution_percentage' => 'required|numeric',
            'employer_contribution_percentage' => 'required|numeric',
        ]);

        $scheme->update($request->all());

        return redirect()->route('pf.schemes.index')->with('success', 'PF Scheme updated successfully.');
    }

    public function destroy(PfScheme $scheme)
    {
        $scheme->delete();
        return redirect()->route('pf.schemes.index')->with('success', 'PF Scheme deleted successfully.');
    }
}
