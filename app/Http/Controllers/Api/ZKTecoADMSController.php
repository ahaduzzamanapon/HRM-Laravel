<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BiometricDevice;
use App\Models\BiometricAttendanceLog;
use App\Models\BiometricCommand;
use App\Models\User;
use App\Models\AttMachineData;
use App\Services\AttendanceService;
use Carbon\Carbon;

class ZKTecoADMSController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Handle the device handshake (GET /iclock/cdata)
     */
    public function handshake(Request $request)
    {
        $sn = $request->query('SN');

        if ($sn) {
            BiometricDevice::updateOrCreate(
                ['serial_number' => $sn],
                [
                    'last_active_at' => now(),
                    'ip_address' => $request->ip()
                ]
            );
        }

        // Return device configuration string
        $r = "GET OPTION FROM: {$sn}\r\n" .
            "Stamp=9999\r\n" .
            "OpStamp=" . time() . "\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "ResLogDay=18250\r\n" .
            "ResLogDelCount=10000\r\n" .
            "ResLogCount=50000\r\n" .
            "TransTimes=00:00;14:05\r\n" .
            "TransInterval=1\r\n" .
            "TransFlag=1111000000\r\n" .
            "Realtime=1\r\n" .
            "Encrypt=0";

        return response($r)->header('Content-Type', 'text/plain');
    }

    /**
     * Handle incoming data from the device (POST /iclock/cdata)
     */
    public function receiveRecords(Request $request)
    {
        $sn = $request->query('SN');
        $table = $request->query('table');

        $device = BiometricDevice::where('serial_number', $sn)->first();
        if ($device) {
            $device->update(['last_active_at' => now(), 'ip_address' => $request->ip()]);
        }

        $content = $request->getContent();
        if (!$content) {
            return response("OK: 0")->header('Content-Type', 'text/plain');
        }

        try {
            $lines = preg_split('/\\r\\n|\\r|\\n/', $content);
            $processedCount = 0;
            $usersToProcess = [];
            $datesToProcess = [];

            if ($table == "OPERLOG") {
                foreach ($lines as $line) {
                    if (trim($line) !== '')
                        $processedCount++;
                }
                return response("OK: {$processedCount}")->header('Content-Type', 'text/plain');
            }

            // ATTLOG table (Attendance)
            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                $data = explode("\t", $line);
                if (count($data) >= 2) {
                    $biometricUserId = $data[0];
                    $timestamp = $data[1];
                    $statusCode = $this->validateAndFormatInteger($data[2] ?? null);
                    $verifyMode = $this->validateAndFormatInteger($data[3] ?? null);

                    if ($device) {
                        // Store the raw log mapping
                        BiometricAttendanceLog::create([
                            'biometric_device_id' => $device->id,
                            'biometric_user_id' => $biometricUserId,
                            'timestamp' => $timestamp,
                            'status_code' => $statusCode,
                            'verify_mode' => $verifyMode,
                        ]);
                    }

                    // Auto-Attendance Integration
                    $user = User::where('biometric_id', $biometricUserId)->first();
                    if ($user) {
                        try {
                            $dateTime = Carbon::parse($timestamp);
                            $dateStr = $dateTime->format('Y-m-d');

                            // Also mirror to att_machine_data if punch_id is set
                            if ($user->punch_id) {
                                AttMachineData::firstOrCreate([
                                    'punch_id' => $user->punch_id,
                                    'date_time' => $dateTime->format('Y-m-d H:i:s'),
                                ], [
                                    'device_id' => $device ? $device->id : null,
                                ]);
                            }

                            // Track all matched users for attendance processing
                            $usersToProcess[$user->id] = true;
                            $datesToProcess[$dateStr] = true;
                        } catch (\Exception $e) {
                            \Log::error("Failed to parse biometric timestamp: {$timestamp}");
                        }
                    }

                    $processedCount++;
                }
            }

            // Trigger the official attendance processing job for the affected users and dates
            foreach (array_keys($datesToProcess) as $date) {
                if (!empty($usersToProcess)) {
                    $this->attendanceService->attn_process($date, array_keys($usersToProcess));
                }
            }

            return response("OK: {$processedCount}")->header('Content-Type', 'text/plain');

        } catch (\Throwable $e) {
            \Log::error("ADMS Receive Error: " . $e->getMessage());
            return response("ERROR: 0\n")->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Provide requests to the device (GET /iclock/getrequest)
     */
    public function getrequest(Request $request)
    {
        $sn = $request->query('SN');
        $device = BiometricDevice::where('serial_number', $sn)->first();

        if ($device) {
            $device->update(['last_active_at' => now(), 'ip_address' => $request->ip()]);

            $pendingCommand = BiometricCommand::where('biometric_device_id', $device->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($pendingCommand) {
                $pendingCommand->update(['status' => 'sent', 'sent_at' => now()]);
                // Format: C:<id>:<command_string>
                $commandId = $pendingCommand->id;
                $cmdString = $pendingCommand->command_string;
                return response("C:{$commandId}:{$cmdString}")->header('Content-Type', 'text/plain');
            }
        }

        return response("OK")->header('Content-Type', 'text/plain');
    }

    /**
     * Handle command execution response from the device (POST /iclock/devicecmd)
     */
    public function devicecmd(Request $request)
    {
        // Body format: ID=<command_id>&Return=<status>
        $content = $request->getContent();
        parse_str($content, $params);

        if (isset($params['ID']) && isset($params['Return'])) {
            $command = BiometricCommand::find($params['ID']);
            if ($command) {
                if ((int) $params['Return'] >= 0) {
                    $command->update(['status' => 'executed', 'executed_at' => now()]);
                } else {
                    $command->update(['status' => 'failed']);
                }
            }
        }

        return response("OK")->header('Content-Type', 'text/plain');
    }

    private function validateAndFormatInteger($value)
    {
        return isset($value) && $value !== '' ? (int) $value : null;
    }
}
