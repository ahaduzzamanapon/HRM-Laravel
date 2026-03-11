<?php

namespace App\Http\Controllers\Api;

use App\Models\BiometricCommand;
use Illuminate\Http\Request;

class BiometricCommandApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = BiometricCommand::with('device')
            ->when($request->device_id, fn($q) => $q->where('device_id', $request->device_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:biometric_devices,id',
            'command' => 'required|string|max:500',
            'params' => 'nullable|string',
        ]);
        $validated['status'] = 'pending';
        $item = BiometricCommand::create($validated);
        return $this->successResponse($item->load('device'), 'Command queued successfully', 201);
    }

    public function show($id)
    {
        $item = BiometricCommand::with('device')->find($id);
        if (!$item)
            return $this->errorResponse('Command not found', 404);
        return $this->successResponse($item);
    }

    public function destroy($id)
    {
        $item = BiometricCommand::find($id);
        if (!$item)
            return $this->errorResponse('Command not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Command deleted successfully');
    }
}
