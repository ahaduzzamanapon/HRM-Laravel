<?php

namespace App\Http\Controllers\Api;

use App\Models\BiometricAttendanceLog;
use Illuminate\Http\Request;

class BiometricAttendanceLogApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = BiometricAttendanceLog::with(['device'])
            ->when($request->device_id, fn($q) => $q->where('device_id', $request->device_id))
            ->when($request->biometric_id, fn($q) => $q->where('biometric_id', $request->biometric_id))
            ->when($request->date, fn($q) => $q->whereDate('punch_time', $request->date))
            ->when($request->from_date, fn($q) => $q->whereDate('punch_time', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('punch_time', '<=', $request->to_date))
            ->orderByDesc('punch_time')
            ->paginate($request->per_page ?? 30);
        return $this->paginatedResponse($items);
    }

    public function show($id)
    {
        $item = BiometricAttendanceLog::with('device')->find($id);
        if (!$item)
            return $this->errorResponse('Log not found', 404);
        return $this->successResponse($item);
    }
}
