<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('admin.maintenance.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.maintenance.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'status' => 'boolean',
        ]);

        Vendor::create($request->all());

        return redirect()->route('admin.maintenance.vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin.maintenance.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'status' => 'boolean',
        ]);

        $vendor->update($request->all());

        return redirect()->route('admin.maintenance.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        
        if($vendor->maintenanceRequests()->count() > 0) {
            return redirect()->route('admin.maintenance.vendors.index')->with('error', 'Cannot delete vendor because it has associated maintenance requests.');
        }

        $vendor->delete();

        return redirect()->route('admin.maintenance.vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
