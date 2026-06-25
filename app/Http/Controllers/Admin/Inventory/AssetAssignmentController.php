<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetAssignment;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssetAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetAssignment::with(['asset.category', 'employee', 'assignedBy', 'returnedTo']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        $assignments = $query->orderBy('assigned_date', 'desc')->get();
        $employees = User::all();

        return view('admin.inventory.asset_assignments.index', compact('assignments', 'employees'));
    }

    public function create()
    {
        // Only fetch available assets
        $assets = Asset::where('status', 'Available')->get();
        $employees = User::all();
        
        return view('admin.inventory.asset_assignments.create', compact('assets', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'user_id' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after_or_equal:assigned_date',
            'condition_on_assignment' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $asset = Asset::findOrFail($request->asset_id);
        
        // Block double assign or disposed/maintenance assets
        if ($asset->status !== 'Available') {
            return redirect()->back()->with('error', 'Asset is not available for assignment.');
        }

        // Create assignment
        $assignment = AssetAssignment::create([
            'asset_id' => $asset->id,
            'user_id' => $request->user_id,
            'assigned_date' => $request->assigned_date,
            'expected_return_date' => $request->expected_return_date,
            'condition_on_assignment' => $request->condition_on_assignment,
            'status' => 'Assigned',
            'notes' => $request->notes,
            'assigned_by' => Auth::id() ?? 1, // Fallback if auth missing in seeder/CLI context
        ]);

        // Auto status update on asset
        $asset->update(['status' => 'Assigned']);

        // Log the event
        \App\Models\AssetLog::logEvent($asset->id, 'Assigned', 'Available', 'Assigned', $request->notes, $request->user_id, $asset->department_id, $assignment);

        // Send Notification
        $user = User::find($request->user_id);
        if ($user) {
            $user->notify(new \App\Notifications\AssetAssignedNotification($assignment));
        }

        return redirect()->route('admin.inventory.asset-assignments.index')->with('success', 'Asset assigned successfully.');
    }

    public function returnForm($id)
    {
        $assignment = AssetAssignment::with(['asset', 'employee'])->findOrFail($id);
        
        if ($assignment->status === 'Returned') {
            return redirect()->route('admin.inventory.asset-assignments.index')->with('error', 'This asset is already returned.');
        }

        return view('admin.inventory.asset_assignments.return', compact('assignment'));
    }

    public function processReturn(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'condition_on_return' => 'required|string|max:255',
            'asset_status' => 'required|in:Available,Maintenance,Retired,Disposed',
            'notes' => 'nullable|string',
        ]);

        $assignment = AssetAssignment::findOrFail($id);
        
        if ($assignment->status === 'Returned') {
            return redirect()->back()->with('error', 'This asset is already returned.');
        }

        $assignment->update([
            'return_date' => $request->return_date,
            'condition_on_return' => $request->condition_on_return,
            'status' => 'Returned',
            'returned_to' => Auth::id() ?? 1,
            'notes' => $assignment->notes ? $assignment->notes . "\nReturn Notes: " . $request->notes : $request->notes,
        ]);

        // Update asset status based on condition
        $assignment->asset->update(['status' => $request->asset_status]);

        // Log the event
        \App\Models\AssetLog::logEvent($assignment->asset_id, 'Returned', 'Assigned', $request->asset_status, 'Asset returned. Condition: ' . $request->condition_on_return . '. Notes: ' . $request->notes, $assignment->user_id, $assignment->asset->department_id, $assignment);

        return redirect()->route('admin.inventory.asset-assignments.index')->with('success', 'Asset returned successfully.');
    }
}
