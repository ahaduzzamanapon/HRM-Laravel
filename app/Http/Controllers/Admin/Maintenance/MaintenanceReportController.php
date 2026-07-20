<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Vendor;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GenericReportExport;

class MaintenanceReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('layout') === 'print' && app()->bound('debugbar')) {
            app('debugbar')->disable();
        }

        $assets = Asset::all();
        $vendors = Vendor::all();

        $query = MaintenanceRequest::query();

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('requested_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('requested_date', '<=', $request->to_date);
        }

        $requests = $query->with(['asset', 'vendor', 'type'])->get();

        $totalCost = $requests->sum('cost');

        // Cost by vendor
        $vendorCosts = $requests->groupBy('vendor_id')->map(function ($row) {
            return $row->sum('cost');
        });

        // Cost by asset
        $assetCosts = $requests->groupBy('asset_id')->map(function ($row) {
            return $row->sum('cost');
        });

        return view('admin.maintenance.reports.index', compact('requests', 'assets', 'vendors', 'totalCost', 'vendorCosts', 'assetCosts'));
    }

    public function export(Request $request)
    {
        $query = MaintenanceRequest::query();

        if ($request->filled('asset_id')) $query->where('asset_id', $request->asset_id);
        if ($request->filled('vendor_id')) $query->where('vendor_id', $request->vendor_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('from_date')) $query->whereDate('requested_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->whereDate('requested_date', '<=', $request->to_date);

        $requests = $query->with(['asset', 'vendor', 'type'])->get();

        $headers = ['ID', 'Asset', 'Vendor', 'Type', 'Requested Date', 'Completed Date', 'Cost', 'Status'];
        $exportRecords = [];
        
        foreach($requests as $req) {
            $exportRecords[] = [
                $req->id,
                optional($req->asset)->name,
                optional($req->vendor)->name,
                optional($req->type)->name,
                $req->requested_date,
                $req->completed_date,
                $req->cost,
                $req->status
            ];
        }

        $viewData = ['title' => 'Maintenance Report', 'headers' => $headers, 'records' => $exportRecords];

        if ($request->export == 'pdf') {
            $viewData['isExcel'] = false;
            $pdf = Pdf::loadView('admin.inventory.reports.export', $viewData);
            return $pdf->download("maintenance_reports_" . date('Y-m-d') . ".pdf");
        } else {
            $viewData['isExcel'] = true;
            return Excel::download(new GenericReportExport('admin.inventory.reports.export', $viewData), "maintenance_reports_" . date('Y-m-d') . ".xlsx");
        }
    }
}
