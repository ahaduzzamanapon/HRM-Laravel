<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiometricAttendanceLog;
use App\Models\BiometricDevice;
use Illuminate\Http\Request;

class ZktecoAttendanceController extends Controller
{
    /**
     * Receive attendance punch from ZKTeco Live Capture desktop app.
     *
     * Expected payload:
     * {
     *   "member_id":   "123",
     *   "timestamp":   "2026-05-06 11:30:45",
     *   "device_ip":   "192.168.1.100",
     *   "device_name": "Main Entrance"
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'   => 'required|string|max:100',
            'timestamp'   => 'required|date',
            'device_ip'   => 'required|string|max:50',
            'device_name' => 'nullable|string|max:255',
        ]);

        // Auto-register device if not seen before (keyed by ip_address).
        // serial_number is required/unique in the table, so we use ip_address there.
        $device = BiometricDevice::firstOrCreate(
            ['serial_number' => $data['device_ip']],
            [
                'name'           => $data['device_name'] ?? $data['device_ip'],
                'ip_address'     => $data['device_ip'],
                'last_active_at' => now(),
            ]
        );

        // Update ip/name and last_active_at on every punch
        $device->update([
            'ip_address'     => $data['device_ip'],
            'name'           => $data['device_name'] ?? $device->name,
            'last_active_at' => now(),
        ]);

        // Save the attendance log
        BiometricAttendanceLog::create([
            'biometric_device_id' => $device->id,
            'biometric_user_id'   => $data['member_id'],
            'timestamp'           => $data['timestamp'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded',
            'data'    => [
                'member_id'   => $data['member_id'],
                'timestamp'   => $data['timestamp'],
                'device'      => $device->name,
            ],
        ], 200);
    }
}
