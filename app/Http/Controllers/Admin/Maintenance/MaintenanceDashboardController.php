<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Vendor;
use App\Models\Asset;
use App\Models\MaintenanceType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaintenanceDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $sevenDaysFromNow = Carbon::today()->addDays(7);
        $thirtyDaysFromNow = Carbon::today()->addDays(30);

        // 1. Setup stats
        $totalVendors = Vendor::count();
        $totalTypes = MaintenanceType::count();

        // 2. Process stats (Workflow statuses)
        $statusCounts = MaintenanceRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statuses = ['Pending', 'Assigned', 'In Progress', 'Completed', 'Cancelled'];
        $stats = [];
        foreach ($statuses as $status) {
            $stats[$status] = $statusCounts[$status] ?? 0;
        }

        // 3. Scheduling (Due and Overdue tracking)
        // Overdue detection: Scheduled date is in the past, and status is not Completed or Cancelled
        $overdueRequests = MaintenanceRequest::with(['asset', 'vendor', 'type'])
            ->where('scheduled_date', '<', $today)
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // Due today or upcoming (next 7 days) and not completed/cancelled
        $dueRequests = MaintenanceRequest::with(['asset', 'vendor', 'type'])
            ->whereBetween('scheduled_date', [$today, $sevenDaysFromNow])
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // Calendar Events
        $calendarEvents = MaintenanceRequest::with(['asset', 'type'])
            ->whereNotNull('scheduled_date')
            ->get()
            ->map(function ($req) {
                $color = '#6c757d'; // default gray
                if ($req->status == 'Completed') {
                    $color = '#28a745'; // green
                } elseif ($req->status == 'In Progress') {
                    $color = '#17a2b8'; // info cyan
                } elseif ($req->status == 'Assigned') {
                    $color = '#007bff'; // blue
                } elseif ($req->status == 'Pending') {
                    $color = '#ffc107'; // yellow
                }
                
                return [
                    'id' => $req->id,
                    'title' => ($req->asset ? $req->asset->name : 'Asset') . ' - ' . $req->title,
                    'start' => $req->scheduled_date,
                    'color' => $color,
                    'description' => 'Type: ' . ($req->type ? $req->type->name : 'N/A') . ', Status: ' . $req->status,
                    'url' => route('admin.maintenance.requests.edit', $req->id)
                ];
            });

        // 4. Cost tracking & summaries
        $totalCost = MaintenanceRequest::sum('cost');

        // Vendor Cost Summary
        $vendorCosts = MaintenanceRequest::select('vendor_id', DB::raw('SUM(cost) as total_cost'))
            ->with('vendor')
            ->whereNotNull('vendor_id')
            ->groupBy('vendor_id')
            ->orderBy('total_cost', 'desc')
            ->get();

        // Asset-wise Cost Summary
        $assetCosts = MaintenanceRequest::select('asset_id', DB::raw('SUM(cost) as total_cost'))
            ->with('asset')
            ->groupBy('asset_id')
            ->orderBy('total_cost', 'desc')
            ->take(10) // Top 10 assets by maintenance cost
            ->get();

        // 5. Recent Requests & History
        $recentRequests = MaintenanceRequest::with(['asset', 'vendor', 'type'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 6. Alerts
        // Maintenance due alerts: Scheduled date is today or tomorrow
        $dueAlerts = MaintenanceRequest::with(['asset', 'vendor'])
            ->whereIn('scheduled_date', [$today, Carbon::tomorrow()])
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->get();

        // Warranty Expiry Alerts (both Assets and Maintenance Requests)
        $expiringAssetWarranties = Asset::whereBetween('warranty_expiry_date', [$today, $thirtyDaysFromNow])
            ->orderBy('warranty_expiry_date', 'asc')
            ->get();

        $expiringMaintenanceWarranties = MaintenanceRequest::with('asset')
            ->whereBetween('warranty_expiry_date', [$today, $thirtyDaysFromNow])
            ->orderBy('warranty_expiry_date', 'asc')
            ->get();

        return view('admin.maintenance.index', compact(
            'totalVendors',
            'totalTypes',
            'stats',
            'overdueRequests',
            'dueRequests',
            'calendarEvents',
            'totalCost',
            'vendorCosts',
            'assetCosts',
            'recentRequests',
            'dueAlerts',
            'expiringAssetWarranties',
            'expiringMaintenanceWarranties'
        ));
    }
}
