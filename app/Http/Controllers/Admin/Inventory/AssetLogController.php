<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetLog;
use App\Models\Asset;
use App\Models\User;
use App\Models\Department;

class AssetLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetLog::with(['asset', 'employee', 'department', 'actionBy'])->orderBy('created_at', 'desc');

        if ($request->has('asset_id') && $request->asset_id != '') {
            $query->where('asset_id', $request->asset_id);
        }
        
        if ($request->has('event_type') && $request->event_type != '') {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(20);
        
        $assets = Asset::all();
        $employees = User::all();
        $departments = Department::all();

        return view('admin.inventory.asset_logs.index', compact('logs', 'assets', 'employees', 'departments'));
    }
}
