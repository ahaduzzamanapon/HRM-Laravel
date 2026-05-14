<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttMachineData;
use App\Models\BiometricAttendanceLog;
use App\Models\BiometricDevice;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
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

        // Auto-register device if not seen before
        $device = BiometricDevice::firstOrCreate(
            ['serial_number' => $data['device_ip']],
            [
                'name'           => $data['device_name'] ?? $data['device_ip'],
                'ip_address'     => $data['device_ip'],
                'last_active_at' => now(),
            ]
        );

        $device->update([
            'ip_address'     => $data['device_ip'],
            'name'           => $data['device_name'] ?? $device->name,
            'last_active_at' => now(),
        ]);

        // Save raw biometric log
        BiometricAttendanceLog::create([
            'biometric_device_id' => $device->id,
            'biometric_user_id'   => $data['member_id'],
            'timestamp'           => $data['timestamp'],
        ]);

        // Auto-trigger attendance processing for matched user
        $user = User::where('biometric_id', $data['member_id'])->first();
        if ($user) {
            $dateStr = Carbon::parse($data['timestamp'])->format('Y-m-d');

            // Mirror to att_machine_data if punch_id is set
            if ($user->punch_id) {
                AttMachineData::firstOrCreate([
                    'punch_id'  => $user->punch_id,
                    'date_time' => Carbon::parse($data['timestamp'])->format('Y-m-d H:i:s'),
                ], ['device_id' => $device->id]);
            }

            // Process attendance immediately
            app(AttendanceService::class)->attn_process($dateStr, [$user->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded',
            'data'    => [
                'member_id' => $data['member_id'],
                'timestamp' => $data['timestamp'],
                'device'    => $device->name,
            ],
        ], 200);
    }
}
