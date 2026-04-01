<?php

namespace App\Http\Controllers\Api;

use App\Models\LeaveApplication;
use Illuminate\Http\Request;

class LeaveApplicationApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $items = LeaveApplication::with(['leaveType'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->leave_type_id, fn($q) => $q->where('leave_type_id', $request->leave_type_id))
            ->when($request->year, fn($q) => $q->whereYear('start_date', $request->year))
            // Employees see only their own unless admin
            ->when(!$user->group_id || $user->group_id > 2, fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'is_half_day' => 'nullable|boolean',
        ]);
        $validated['requested_days'] = \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;


        $validated['status'] = 'pending';
        $item = LeaveApplication::create($validated);
        return $this->successResponse(null,'Leave application submitted successfully', 201);
    }

    public function show($id)
    {
        $item = LeaveApplication::with(['user', 'leaveType'])->find($id);
        if (!$item)
            return $this->errorResponse('Leave application not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = LeaveApplication::find($id);
        if (!$item)
            return $this->errorResponse('Leave application not found', 404);
        if ($item->status !== 'pending') {
            return $this->errorResponse('Cannot update a processed leave application', 422);
        }
        $validated = $request->validate([
            'leave_type_id' => 'sometimes|exists:leave_types,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'is_half_day' => 'nullable|boolean',
        ]);

        if (isset($validated['start_date']) && isset($validated['end_date'])) {
            $validated['requested_days'] = \Carbon\Carbon::parse($validated['start_date'])->diffInDays(\Carbon\Carbon::parse($validated['end_date'])) + 1;
        }

        $item->update($validated);
        return $this->successResponse($item->load(['user', 'leaveType']), 'Leave application updated successfully');
    }

    public function destroy($id)
    {
        $item = LeaveApplication::find($id);
        if (!$item)
            return $this->errorResponse('Leave application not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Leave application deleted successfully');
    }

    public function firstApprove(Request $request, $id)
    {
        $item = LeaveApplication::find($id);
        if (!$item)
            return $this->errorResponse('Leave application not found', 404);
        $item->update([
            'first_approval_status' => 'approved',
            'first_approved_by' => $request->user()->id,
            'first_approved_at' => now(),
        ]);
        return $this->successResponse($item, 'First-level approval granted');
    }

    public function finalApprove(Request $request, $id)
    {
        $item = LeaveApplication::find($id);
        if (!$item)
            return $this->errorResponse('Leave application not found', 404);
        $item->update([
            'status' => 'approved',
            'final_approved_by' => $request->user()->id,
            'final_approved_at' => now(),
        ]);
        return $this->successResponse($item, 'Final approval granted');
    }

    public function reject(Request $request, $id)
    {
        $item = LeaveApplication::find($id);
        if (!$item)
            return $this->errorResponse('Leave application not found', 404);
        $item->update([
            'status' => 'rejected',
            'rejected_by' => $request->user()->id,
            'rejected_at' => now(),
            'reject_reason' => $request->reason,
        ]);
        return $this->successResponse($item, 'Leave application rejected');
    }
}
