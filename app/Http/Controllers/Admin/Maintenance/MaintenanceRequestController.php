<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceType;
use App\Models\Vendor;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['asset', 'type', 'vendor', 'requestedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        $requests = $query->orderBy('id', 'desc')->get();
        $assets = Asset::all();

        return view('admin.maintenance.requests.index', compact('requests', 'assets'));
    }

    public function create()
    {
        $assets = Asset::all();
        $types = MaintenanceType::where('status', 1)->get();
        $vendors = Vendor::where('status', 1)->get();
        return view('admin.maintenance.requests.create', compact('assets', 'types', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type_id' => 'required|exists:maintenance_types,id',
            'title' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'requested_date' => 'required|date',
        ]);

        $data = $request->all();
        $data['requested_by'] = Auth::id();

        MaintenanceRequest::create($data);

        return redirect()->route('admin.maintenance.requests.index')->with('success', 'Maintenance Request created successfully.');
    }

    public function edit($id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $assets = Asset::all();
        $types = MaintenanceType::where('status', 1)->get();
        $vendors = Vendor::where('status', 1)->get();
        return view('admin.maintenance.requests.edit', compact('maintenanceRequest', 'assets', 'types', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type_id' => 'required|exists:maintenance_types,id',
            'title' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'status' => 'required|in:Pending,Assigned,In Progress,Completed,Cancelled',
            'requested_date' => 'required|date',
        ]);

        $maintenanceRequest->update($request->all());

        return redirect()->route('admin.maintenance.requests.index')->with('success', 'Maintenance Request updated successfully.');
    }

    public function destroy($id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $maintenanceRequest->delete();

        return redirect()->route('admin.maintenance.requests.index')->with('success', 'Maintenance Request deleted successfully.');
    }
}
