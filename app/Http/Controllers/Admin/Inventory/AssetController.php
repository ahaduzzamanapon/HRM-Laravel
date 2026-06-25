<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'department']);

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        $assets = $query->get();
        $categories = AssetCategory::all();
        $departments = Department::all();

        return view('admin.inventory.assets.index', compact('assets', 'categories', 'departments'));
    }

    public function create()
    {
        $categories = AssetCategory::where('status', 1)->get();
        $departments = Department::all();
        return view('admin.inventory.assets.create', compact('categories', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:asset_categories,id',
            'asset_code' => 'required|unique:assets,asset_code',
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|unique:assets,serial_number',
            'purchase_cost' => 'nullable|numeric',
            'status' => 'required|in:Available,Assigned,Maintenance,Retired,Disposed',
        ]);

        $asset = Asset::create($request->all());

        \App\Models\AssetLog::logEvent($asset->id, 'Registered', null, $asset->status, 'Asset initially registered', null, $asset->department_id, $asset);

        return redirect()->route('admin.inventory.assets.index')->with('success', 'Asset created successfully.');
    }

    public function show($id)
    {
        $asset = Asset::with(['category', 'department'])->findOrFail($id);
        
        // Also fetch timeline logs for this asset
        $logs = \App\Models\AssetLog::with(['employee', 'department', 'actionBy'])
            ->where('asset_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.inventory.assets.show', compact('asset', 'logs'));
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $categories = AssetCategory::where('status', 1)->get();
        $departments = Department::all();
        return view('admin.inventory.assets.edit', compact('asset', 'categories', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        $oldStatus = $asset->status;

        $request->validate([
            'category_id' => 'required|exists:asset_categories,id',
            'asset_code' => 'required|unique:assets,asset_code,' . $asset->id,
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|unique:assets,serial_number,' . $asset->id,
            'purchase_cost' => 'nullable|numeric',
            'status' => 'required|in:Available,Assigned,Maintenance,Retired,Disposed',
        ]);

        $asset->update($request->all());

        \App\Models\AssetLog::logEvent($asset->id, 'Updated', $oldStatus, $asset->status, 'Asset details updated', null, $asset->department_id, $asset);

        return redirect()->route('admin.inventory.assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        
        // Prevent deletion if assigned or in maintenance
        if (in_array($asset->status, ['Assigned', 'Maintenance'])) {
            return redirect()->route('admin.inventory.assets.index')->with('error', 'Cannot delete asset because it is currently assigned or in maintenance.');
        }

        $oldStatus = $asset->status;
        
        // Let's not physically delete it, just change status? Wait, user code physically deletes it.
        // So we log before delete. Actually if we delete asset, and logs are cascaded... they will be deleted.
        // Let's remove cascade on delete? Or we can let it cascade. If it cascades, no point logging 'Deleted'.
        // Let's just keep the original delete behavior.
        
        $asset->delete();

        return redirect()->route('admin.inventory.assets.index')->with('success', 'Asset deleted successfully.');
    }

    public function addLog(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        
        $request->validate([
            'event_type' => 'required|string',
            'notes' => 'required|string',
            'new_status' => 'nullable|string|in:Available,Maintenance,Retired,Disposed'
        ]);

        $oldStatus = $asset->status;
        $newStatus = $oldStatus;

        if ($request->new_status && $request->new_status !== $oldStatus) {
            $newStatus = $request->new_status;
            $asset->update(['status' => $newStatus]);
        }

        \App\Models\AssetLog::logEvent($asset->id, $request->event_type, $oldStatus, $newStatus, $request->notes, null, $asset->department_id);

        return redirect()->route('admin.inventory.assets.show', $asset->id)->with('success', 'Manual log entry added successfully.');
    }
}
