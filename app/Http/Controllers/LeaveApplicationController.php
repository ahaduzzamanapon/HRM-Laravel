<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Flash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of leave applications with stats, filters, and balances.
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);

        $canManageLeaves = !$isEmployeeRole && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'));
        $canSelectEmployee = !$isEmployeeRole;

        $query = LeaveApplication::with(['user.branch', 'user.department', 'leaveType', 'approver', 'finalApprover'])
            ->orderBy('created_at', 'desc');

        if ($isEmployeeRole) {
            $query->where('user_id', $authUser->id);
        } else {
            applyUserBranchScope($query, 'user');

            if ($request->filled('branch_id')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                });
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('emp_id', 'like', "%{$search}%");
            });
        }

        $leaveApplications = $query->paginate(15);

        // Stats calculation
        $statsQuery = LeaveApplication::query();
        if ($isEmployeeRole) {
            $statsQuery->where('user_id', $authUser->id);
        } else {
            applyUserBranchScope($statsQuery, 'user');
        }
        $totalCount = (clone $statsQuery)->count();
        $pendingCount = (clone $statsQuery)->where('status', 'Pending')->count();
        $approvedCount = (clone $statsQuery)->whereIn('status', ['Approved', 'First Level Approved'])->count();
        $rejectedCount = (clone $statsQuery)->where('status', 'Rejected')->count();

        // Calculate leave balances for the logged-in user or employee (only for employee view)
        $targetUser = $authUser;
        $userLeaveBalances = [];

        if ($isEmployeeRole) {
            $userAssigned = \App\Models\UserLeaveAssignment::where('user_id', $targetUser->id)->pluck('allowed_days', 'leave_type_id')->toArray();

            $allTypes = LeaveType::all();
            $allowedLeaveTypesList = [];

            foreach ($allTypes as $type) {
                if (!array_key_exists($type->id, $userAssigned)) {
                    continue; // Skip leave types not explicitly assigned by Admin to this employee
                }
                // Gender criteria check
                if ($type->gender_criteria !== 'All' && !empty($type->gender_criteria) && $targetUser->gender && strtolower($type->gender_criteria) !== strtolower($targetUser->gender)) {
                    continue;
                }

                $allowedLeaveTypesList[] = $type;

                $totalDays = (isset($userAssigned[$type->id]) && $userAssigned[$type->id] !== null) ? $userAssigned[$type->id] : $type->total_days_per_year;
                $usedDays = LeaveApplication::where('user_id', $targetUser->id)
                    ->where('leave_type_id', $type->id)
                    ->where('status', 'Approved')
                    ->whereYear('start_date', now()->year)
                    ->sum('requested_days');

                $userLeaveBalances[] = [
                    'id' => $type->id,
                    'name' => $type->name,
                    'total' => $totalDays,
                    'used' => $usedDays,
                    'remaining' => max(0, $totalDays - $usedDays),
                ];
            }
            $leaveTypes = collect($allowedLeaveTypesList);
        } else {
            $leaveTypes = LeaveType::all();
        }

        $branchesQuery = Branch::query();
        applyBranchScope($branchesQuery, 'id');
        $branches = $branchesQuery->pluck('branch_name', 'id');

        $employees = null;
        if ($canSelectEmployee) {
            $employeesQuery = User::where('group_id', '!=', 1)->where('status', '!=', 'left');
            applyBranchScope($employeesQuery, 'branch_id');
            $employees = $employeesQuery->select('id', 'name', 'last_name', 'emp_id')->get();
        }

        return view('leave_applications.index', compact(
            'leaveApplications',
            'leaveTypes',
            'branches',
            'employees',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'userLeaveBalances',
            'canManageLeaves',
            'canSelectEmployee'
        ));
    }

    /**
     * Show the form for creating a new leave application.
     */
    public function create()
    {
        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);

        $canManageLeaves = !$isEmployeeRole && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'));
        $canSelectEmployee = !$isEmployeeRole;

        $userAssigned = \App\Models\UserLeaveAssignment::where('user_id', $authUser->id)->pluck('allowed_days', 'leave_type_id')->toArray();

        $allTypes = LeaveType::all();
        $leaveBalances = [];
        $availableTypesForDropdown = [];

        foreach ($allTypes as $type) {
            if ($isEmployeeRole && !array_key_exists($type->id, $userAssigned)) {
                continue;
            }
            if ($type->gender_criteria !== 'All' && !empty($type->gender_criteria) && $authUser->gender && strtolower($type->gender_criteria) !== strtolower($authUser->gender)) {
                continue;
            }

            $availableTypesForDropdown[$type->id] = $type->name;

            $totalDays = (isset($userAssigned[$type->id]) && $userAssigned[$type->id] !== null) ? $userAssigned[$type->id] : $type->total_days_per_year;
            $usedDays = LeaveApplication::where('user_id', $authUser->id)
                ->where('leave_type_id', $type->id)
                ->where('status', 'Approved')
                ->whereYear('start_date', now()->year)
                ->sum('requested_days');

            $leaveBalances[] = [
                'name' => $type->name,
                'total' => $totalDays,
                'used' => $usedDays,
                'remaining' => max(0, $totalDays - $usedDays),
            ];
        }

        if ($canManageLeaves) {
            $leaveTypes = LeaveType::pluck('name', 'id');
        } else {
            $leaveTypes = collect($availableTypesForDropdown);
        }

        $employees = null;
        if ($canSelectEmployee) {
            $empQuery = User::where('group_id', '!=', 1)->where('status', 'active');
            applyBranchScope($empQuery, 'branch_id');
            $employees = $empQuery->select('id', 'name', 'last_name', 'emp_id')->get();
        }

        return view('leave_applications.create', compact('leaveTypes', 'leaveBalances', 'employees', 'canManageLeaves', 'canSelectEmployee'));
    }

    /**
     * Store a newly created leave application in storage.
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);

        $canManageLeaves = !$isEmployeeRole && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'));
        $canSelectEmployee = !$isEmployeeRole;

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $targetUserId = ($isEmployeeRole || !$canSelectEmployee || !$request->filled('user_id')) ? $authUser->id : $request->user_id;

        $targetUser = User::find($targetUserId);
        if (!$targetUser) {
            Flash::error('Selected employee not found.');
            return redirect()->back()->withInput();
        }

        if ($canManageLeaves && $targetUser->id !== $authUser->id) {
            checkBranchAccess($targetUser->branch_id);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        if ($request->has('is_half_day') && $request->is_half_day) {
            $requestedDays = 0.5;
        }

        $leaveType = LeaveType::find($request->leave_type_id);

        // Gender criteria check
        if ($leaveType->gender_criteria !== 'All' && $targetUser->gender && strtolower($leaveType->gender_criteria) !== strtolower($targetUser->gender)) {
            Flash::error('This leave type is not applicable for employee gender (' . $targetUser->gender . ').');
            return redirect()->back()->withInput();
        }

        // Leave balance check
        $usedLeaves = LeaveApplication::where('user_id', $targetUserId)
            ->where('leave_type_id', $leaveType->id)
            ->where('status', 'Approved')
            ->whereYear('start_date', now()->year)
            ->sum('requested_days');

        $availableLeave = $leaveType->total_days_per_year;
        if (($usedLeaves + $requestedDays) > $availableLeave && !$canManageLeaves) {
            Flash::error("Insufficient leave balance. Available: " . max(0, $availableLeave - $usedLeaves) . " days, Requested: {$requestedDays} days.");
            return redirect()->back()->withInput();
        }

        // Overlapping leave check
        $overlap = LeaveApplication::where('user_id', $targetUserId)
            ->whereIn('status', ['Pending', 'First Level Approved', 'Approved'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            })->first();

        if ($overlap) {
            $stFormatted = Carbon::parse($overlap->start_date)->format('M d, Y');
            $enFormatted = Carbon::parse($overlap->end_date)->format('M d, Y');
            Flash::error("⚠️ Cannot apply for the same date! An active leave application ({$overlap->status}) already exists for this employee from {$stFormatted} to {$enFormatted}.");
            return redirect()->back()->withInput();
        }

        $status = ($request->filled('status') && $canManageLeaves) ? $request->status : 'Pending';

        $input = [
            'user_id' => $targetUserId,
            'leave_type_id' => $leaveType->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'is_half_day' => $request->has('is_half_day') ? 1 : 0,
            'requested_days' => $requestedDays,
            'reason' => $request->reason,
            'status' => $status,
            'approver_level' => ($status === 'Approved') ? 'approved' : 'first_level',
            'approved_by' => ($status === 'Approved') ? $authUser->id : null,
            'approved_at' => ($status === 'Approved') ? now() : null,
        ];

        $leaveApplication = LeaveApplication::create($input);

        // Notify Admin and HR users about the new leave application
        try {
            $admins = User::where('group_id', 1)
                ->orWhere('status', 'admin')
                ->orWhereHas('role', function ($q) {
                    $q->whereIn('name', ['Admin', 'Super Admin', 'HR']);
                })
                ->get();

            // Filter out the applicant themselves if they submitted it
            $adminsToNotify = $admins->reject(function ($u) use ($authUser) {
                return $u->id === $authUser->id;
            });

            if ($adminsToNotify->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($adminsToNotify, new \App\Notifications\LeaveAppliedNotification($leaveApplication));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Leave Applied Notification Error: ' . $e->getMessage());
        }

        Flash::success('Leave Application submitted successfully.');
        return redirect(route('leaveApplications.index'));
    }

    /**
     * Display the specified leave application.
     */
    public function show($id, Request $request)
    {
        $leaveApplication = LeaveApplication::with(['user.branch', 'user.department', 'leaveType', 'approver', 'finalApprover'])->find($id);

        if (empty($leaveApplication)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Leave Application not found.'], 404);
            }
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser) || (isset($authUser->role_id) && (int)$authUser->role_id === 3) || (isset($authUser->group_id) && (int)$authUser->group_id === 3);
        $canManageLeaves = !$isEmployeeRole && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'));

        if ($isEmployeeRole && (int)$leaveApplication->user_id !== (int)$authUser->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
            }
            Flash::error('Unauthorized access.');
            return redirect(route('leaveApplications.index'));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $leaveApplication->id,
                    'user_name' => ($leaveApplication->user->name ?? 'N/A') . ' ' . ($leaveApplication->user->last_name ?? ''),
                    'emp_id' => $leaveApplication->user->emp_id ?? 'N/A',
                    'branch_name' => optional(optional($leaveApplication->user)->branch)->branch_name ?? 'N/A',
                    'leave_type_id' => $leaveApplication->leave_type_id,
                    'leave_type_name' => $leaveApplication->leaveType->name ?? 'N/A',
                    'start_date' => \Carbon\Carbon::parse($leaveApplication->start_date)->format('Y-m-d'),
                    'end_date' => \Carbon\Carbon::parse($leaveApplication->end_date)->format('Y-m-d'),
                    'start_date_formatted' => \Carbon\Carbon::parse($leaveApplication->start_date)->format('d M, Y'),
                    'end_date_formatted' => \Carbon\Carbon::parse($leaveApplication->end_date)->format('d M, Y'),
                    'requested_days' => $leaveApplication->requested_days,
                    'is_half_day' => $leaveApplication->is_half_day,
                    'reason' => $leaveApplication->reason,
                    'status' => $leaveApplication->status,
                    'approver_name' => $leaveApplication->approver->name ?? 'N/A',
                    'final_approver_name' => $leaveApplication->finalApprover->name ?? ($leaveApplication->approved_by ? 'Admin' : 'N/A'),
                ],
                'canManageLeaves' => $canManageLeaves
            ]);
        }

        return view('leave_applications.show', compact('leaveApplication', 'canManageLeaves'));
    }

    /**
     * Show the form for editing/modifying the specified leave application.
     */
    public function edit($id, Request $request)
    {
        $leaveApplication = LeaveApplication::with('user')->find($id);

        if (empty($leaveApplication)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Leave Application not found.'], 404);
            }
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser) || (isset($authUser->role_id) && (int)$authUser->role_id === 3) || (isset($authUser->group_id) && (int)$authUser->group_id === 3);
        $canManageLeaves = !$isEmployeeRole && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'));

        if ($isEmployeeRole) {
            if ((int)$leaveApplication->user_id !== (int)$authUser->id) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized access to edit this leave application.'], 403);
                }
                Flash::error('Unauthorized access. You can only edit your own leave applications.');
                return redirect(route('leaveApplications.index'));
            }

            if ($leaveApplication->status !== 'Pending') {
                $msg = 'You cannot edit this leave application because it is already ' . $leaveApplication->status . '. Only pending leave records can be changed.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 403);
                }
                Flash::error($msg);
                return redirect(route('leaveApplications.index'));
            }
        }

        $leaveTypes = LeaveType::pluck('name', 'id');
        $statuses = ['Pending' => 'Pending', 'First Level Approved' => 'First Level Approved', 'Approved' => 'Approved', 'Rejected' => 'Rejected'];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $leaveApplication->id,
                    'user_name' => ($leaveApplication->user->name ?? 'N/A') . ' ' . ($leaveApplication->user->last_name ?? ''),
                    'emp_id' => $leaveApplication->user->emp_id ?? 'N/A',
                    'leave_type_id' => $leaveApplication->leave_type_id,
                    'start_date' => \Carbon\Carbon::parse($leaveApplication->start_date)->format('Y-m-d'),
                    'end_date' => \Carbon\Carbon::parse($leaveApplication->end_date)->format('Y-m-d'),
                    'requested_days' => $leaveApplication->requested_days,
                    'is_half_day' => $leaveApplication->is_half_day,
                    'reason' => $leaveApplication->reason,
                    'status' => $leaveApplication->status,
                ],
                'leaveTypes' => $leaveTypes,
                'statuses' => $statuses,
                'canManageLeaves' => $canManageLeaves
            ]);
        }

        return view('leave_applications.edit', compact('leaveApplication', 'leaveTypes', 'statuses', 'canManageLeaves'));
    }

    /**
     * Update the specified leave application in storage (Modify/Approve/Reject by Admin/HR).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $leaveApplication = LeaveApplication::with('user')->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser) || (isset($authUser->role_id) && (int)$authUser->role_id === 3) || (isset($authUser->group_id) && (int)$authUser->group_id === 3);
        $canManageLeaves = !$isEmployeeRole && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'));

        if ($isEmployeeRole) {
            if ((int)$leaveApplication->user_id !== (int)$authUser->id) {
                Flash::error('Unauthorized access. You can only edit your own leave applications.');
                return redirect(route('leaveApplications.index'));
            }

            if ($leaveApplication->status !== 'Pending') {
                Flash::error('You cannot modify this leave application because it is already ' . $leaveApplication->status . '. Only pending leave records can be changed.');
                return redirect(route('leaveApplications.index'));
            }
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $calculatedDays = $startDate->diffInDays($endDate) + 1;

        if ($request->has('is_half_day') && $request->is_half_day) {
            $requestedDays = 0.5;
        } elseif ($canManageLeaves && $request->filled('requested_days') && is_numeric($request->requested_days) && (float)$request->requested_days > 0) {
            $requestedDays = (float)$request->requested_days;
        } else {
            $requestedDays = $calculatedDays;
        }

        $input = [
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'is_half_day' => $request->has('is_half_day') ? 1 : 0,
            'requested_days' => $requestedDays,
            'reason' => $request->reason,
        ];

        if ($canManageLeaves && $request->filled('status')) {
            $input['status'] = $request->status;
            if ($request->status === 'Approved') {
                $input['approved_by'] = Auth::id();
                $input['approved_at'] = now();
                $input['approver_level'] = 'approved';
            }
        }

        $oldStatus = $leaveApplication->status;
        $leaveApplication->update($input);

        if (isset($input['status']) && $input['status'] !== $oldStatus) {
            try {
                if ($leaveApplication->user) {
                    $leaveApplication->user->notify(new \App\Notifications\LeaveStatusUpdatedNotification($leaveApplication, $input['status']));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Leave Status Notification Error: ' . $e->getMessage());
            }
        }

        Flash::success('Leave Application updated/modified successfully.');
        return redirect(route('leaveApplications.index'));
    }

    /**
     * Remove the specified leave application from storage.
     */
    public function destroy($id)
    {
        $leaveApplication = LeaveApplication::with('user')->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser) || (isset($authUser->role_id) && (int)$authUser->role_id === 3) || (isset($authUser->group_id) && (int)$authUser->group_id === 3);

        if ($isEmployeeRole) {
            if ((int)$leaveApplication->user_id !== (int)$authUser->id) {
                Flash::error('Unauthorized access.');
                return redirect(route('leaveApplications.index'));
            }

            if ($leaveApplication->status !== 'Pending') {
                Flash::error('You cannot cancel or delete this leave application because it is already ' . $leaveApplication->status . '.');
                return redirect(route('leaveApplications.index'));
            }
        }

        $leaveApplication->delete();

        Flash::success('Leave Application deleted successfully.');
        return redirect(route('leaveApplications.index'));
    }

    /**
     * Approve leave application.
     */
    public function approve($id)
    {
        return $this->finalApprove($id);
    }

    public function firstLevelApprove($id)
    {
        if (!isSuperAdmin() && !can('approve_leave')) {
            abort(403, 'You do not have permission to approve or reject leave applications.');
        }

        $leaveApplication = LeaveApplication::with('user')->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $finalApprover = User::whereHas('role', function ($q) {
            $q->where('name', 'Admin');
        })->first();

        $leaveApplication->status = 'First Level Approved';
        $leaveApplication->approver_level = 'final_level';
        $leaveApplication->final_approver_id = $finalApprover ? $finalApprover->id : null;
        $leaveApplication->save();

        try {
            if ($leaveApplication->user) {
                $leaveApplication->user->notify(new \App\Notifications\LeaveStatusUpdatedNotification($leaveApplication, 'First Level Approved'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Leave Status Notification Error: ' . $e->getMessage());
        }

        Flash::success('Leave Application approved at first level.');
        return redirect(route('leaveApplications.index'));
    }

    public function finalApprove($id)
    {
        if (!isSuperAdmin() && !can('approve_leave')) {
            abort(403, 'You do not have permission to approve or reject leave applications.');
        }

        $leaveApplication = LeaveApplication::with('user')->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $leaveApplication->status = 'Approved';
        $leaveApplication->approver_level = 'approved';
        $leaveApplication->approved_by = Auth::id();
        $leaveApplication->approved_at = Carbon::now();
        $leaveApplication->save();

        try {
            if ($leaveApplication->user) {
                $leaveApplication->user->notify(new \App\Notifications\LeaveStatusUpdatedNotification($leaveApplication, 'Approved'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Leave Status Notification Error: ' . $e->getMessage());
        }

        Flash::success('Leave Application approved successfully.');
        return redirect(route('leaveApplications.index'));
    }

    public function reject($id)
    {
        if (!isSuperAdmin() && !can('approve_leave')) {
            abort(403, 'You do not have permission to approve or reject leave applications.');
        }

        $leaveApplication = LeaveApplication::with('user')->find($id);

        if (empty($leaveApplication)) {
            Flash::error('Leave Application not found.');
            return redirect(route('leaveApplications.index'));
        }

        if ($leaveApplication->user) {
            checkBranchAccess($leaveApplication->user->branch_id);
        }

        $leaveApplication->status = 'Rejected';
        $leaveApplication->save();

        try {
            if ($leaveApplication->user) {
                $leaveApplication->user->notify(new \App\Notifications\LeaveStatusUpdatedNotification($leaveApplication, 'Rejected'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Leave Status Notification Error: ' . $e->getMessage());
        }

        Flash::success('Leave Application rejected successfully.');
        return redirect(route('leaveApplications.index'));
    }

    /**
     * Real-time AJAX endpoint to check if date range overlaps with existing leave applications.
     */
    public function checkOverlap(Request $request)
    {
        $authUser = Auth::user();
        $isEmployeeRole = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);

        $targetUserId = ($isEmployeeRole || !$request->filled('user_id')) ? $authUser->id : $request->user_id;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate || !$targetUserId) {
            return response()->json(['overlap' => false]);
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $overlapQuery = LeaveApplication::where('user_id', $targetUserId)
            ->whereIn('status', ['Pending', 'First Level Approved', 'Approved'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start)
                         ->where('end_date', '>=', $end);
                  });
            });

        if ($request->filled('ignore_id')) {
            $overlapQuery->where('id', '!=', $request->ignore_id);
        }

        $existingLeave = $overlapQuery->first();

        if ($existingLeave) {
            $stFormatted = Carbon::parse($existingLeave->start_date)->format('M d, Y');
            $enFormatted = Carbon::parse($existingLeave->end_date)->format('M d, Y');
            return response()->json([
                'overlap' => true,
                'message' => "⚠️ Cannot apply for the same date! An active leave application ({$existingLeave->status}) already exists from {$stFormatted} to {$enFormatted}."
            ]);
        }

        return response()->json(['overlap' => false]);
    }
}