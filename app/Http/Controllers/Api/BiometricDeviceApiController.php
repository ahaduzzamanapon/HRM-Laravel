<?php

namespace App\Http\Controllers\Api;

use App\Models\BiometricDevice;
use Illuminate\Http\Request;

class BiometricDeviceApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = BiometricDevice::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'serial_no' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);
        $item = BiometricDevice::create($validated);
        return $this->successResponse($item, 'Biometric device created successfully', 201);
    }

    public function show($id)
    {
        $item = BiometricDevice::find($id);
        if (!$item)
            return $this->errorResponse('Biometric device not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = BiometricDevice::find($id);
        if (!$item)
            return $this->errorResponse('Biometric device not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Biometric device updated successfully');
    }

    public function destroy($id)
    {
        $item = BiometricDevice::find($id);
        if (!$item)
            return $this->errorResponse('Biometric device not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Biometric device deleted successfully');
    }
}
