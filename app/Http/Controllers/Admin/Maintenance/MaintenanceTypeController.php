<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceType;

class MaintenanceTypeController extends Controller
{
    public function index()
    {
        $types = MaintenanceType::all();
        return view('admin.maintenance.types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.maintenance.types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:maintenance_types,name|max:255',
            'status' => 'boolean',
        ]);

        MaintenanceType::create($request->all());

        return redirect()->route('admin.maintenance.types.index')->with('success', 'Maintenance Type created successfully.');
    }

    public function edit($id)
    {
        $type = MaintenanceType::findOrFail($id);
        return view('admin.maintenance.types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = MaintenanceType::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:maintenance_types,name,' . $type->id,
            'status' => 'boolean',
        ]);

        $type->update($request->all());

        return redirect()->route('admin.maintenance.types.index')->with('success', 'Maintenance Type updated successfully.');
    }

    public function destroy($id)
    {
        $type = MaintenanceType::findOrFail($id);
        
        if($type->maintenanceRequests()->count() > 0) {
            return redirect()->route('admin.maintenance.types.index')->with('error', 'Cannot delete type because it has associated maintenance requests.');
        }

        $type->delete();

        return redirect()->route('admin.maintenance.types.index')->with('success', 'Maintenance Type deleted successfully.');
    }
}
