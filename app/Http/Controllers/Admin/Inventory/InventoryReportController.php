<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetLog;
use App\Models\User;
use App\Models\Department;
use App\Models\Branch;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GenericReportExport;

class InventoryReportController extends Controller
{
    private function getCommonData()
    {
        return [
            'departments' => Department::pluck('name', 'id'),
            'branches' => Branch::pluck('branch_name', 'id'),
            'assets_list' => Asset::pluck('name', 'id'),
            'employees' => User::pluck('name', 'id'),
        ];
    }

    // 2. Asset Reports
    public function assetReports(Request $request)
    {
        $query = Asset::with(['category', 'department']);
        
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('condition')) $query->where('condition', $request->condition);
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);
        if ($request->filled('from_date')) $query->whereDate('purchase_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->whereDate('purchase_date', '<=', $request->to_date);

        $assets = $query->get();
        
        if ($request->has('export')) {
            return $this->exportReport($assets, 'assets', $request->export);
        }

        $data = $this->getCommonData();
        return view('admin.inventory.reports.asset_reports', compact('assets', 'data'));
    }

    // 3. Assignment Reports
    public function assignmentReports(Request $request)
    {
        $query = AssetAssignment::with(['asset', 'employee.department', 'employee.branch']);
        
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('branch_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('assigned_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('assigned_date', '<=', $request->to_date);
        }
        if ($request->filled('is_overdue')) {
            $query->where('status', 'allocated')
                  ->whereNotNull('expected_return_date')
                  ->whereDate('expected_return_date', '<', Carbon::now());
        }
        
        $assignments = $query->orderBy('assigned_date', 'desc')->get();
        
        if ($request->has('export')) {
            return $this->exportReport($assignments, 'assignments', $request->export);
        }

        $data = $this->getCommonData();
        return view('admin.inventory.reports.assignment_reports', compact('assignments', 'data'));
    }

    // 4. Lifecycle Reports
    public function lifecycleReports(Request $request)
    {
        $query = AssetLog::with(['asset', 'user']);
        
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();

        if ($request->has('export')) {
            return $this->exportReport($logs, 'lifecycle', $request->export);
        }

        $data = $this->getCommonData();
        return view('admin.inventory.reports.lifecycle_reports', compact('logs', 'data'));
    }

    // 6. Inventory Reports (Stock)
    public function inventoryReports(Request $request)
    {
        $query = Asset::with('category');
        
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        if ($request->filled('low_stock')) {
            $query->where('status', 'available');
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $inventory = $query->get();

        if ($request->has('export')) {
            return $this->exportReport($inventory, 'inventory', $request->export);
        }

        $stats = [
            'total' => Asset::count(),
            'available' => Asset::where('status', 'available')->count(),
            'allocated' => Asset::where('status', 'allocated')->count(),
            'maintenance' => Asset::where('status', 'maintenance')->count(),
            'retired' => Asset::where('status', 'retired')->count(),
        ];

        $data = $this->getCommonData();
        return view('admin.inventory.reports.inventory_reports', compact('inventory', 'stats', 'data'));
    }

    private function exportReport($records, $type, $format)
    {
        $headers = [];
        $exportRecords = [];
        $title = "Report";

        if ($type == 'assets' || $type == 'inventory') {
            $title = $type == 'assets' ? "Asset Report" : "Inventory Stock Report";
            $headers = ['ID', 'Name', 'Tag', 'Category', 'Department', 'Status', 'Condition', 'Location', 'Purchase Price'];
            foreach($records as $record) {
                $exportRecords[] = [
                    $record->id, 
                    $record->name, 
                    $record->asset_tag, 
                    optional($record->category)->name, 
                    optional($record->department)->name,
                    $record->status, 
                    $record->condition, 
                    $record->location, 
                    $record->purchase_price
                ];
            }
        } elseif ($type == 'assignments') {
            $title = "Asset Assignment Report";
            $headers = ['ID', 'Asset', 'Employee', 'Department', 'Branch', 'Assigned Date', 'Expected Return', 'Status'];
            foreach($records as $record) {
                $exportRecords[] = [
                    $record->id, 
                    optional($record->asset)->name, 
                    optional($record->employee)->name, 
                    optional(optional($record->employee)->department)->name,
                    optional(optional($record->employee)->branch)->branch_name,
                    $record->assigned_date, 
                    $record->expected_return_date, 
                    $record->status
                ];
            }
        } elseif ($type == 'lifecycle') {
            $title = "Asset Lifecycle Report";
            $headers = ['Date', 'Asset', 'Action', 'Performed By', 'Details'];
            foreach($records as $record) {
                $exportRecords[] = [
                    $record->created_at, 
                    optional($record->asset)->name, 
                    $record->action, 
                    optional($record->user)->name, 
                    $record->details
                ];
            }
        }

        $viewData = ['title' => $title, 'headers' => $headers, 'records' => $exportRecords];

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('admin.inventory.reports.export', $viewData);
            return $pdf->download("{$type}_report_" . date('Y-m-d') . ".pdf");
        } else {
            // Excel Export
            return Excel::download(new GenericReportExport('admin.inventory.reports.export', $viewData), "{$type}_report_" . date('Y-m-d') . ".xlsx");
        }
    }
}
