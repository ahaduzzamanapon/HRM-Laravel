<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BiometricAttendanceLog;
use App\Models\AttMachineData;
use App\Models\User;

echo "=== Backfilling biometric_attendance_logs → att_machine_data ===" . PHP_EOL;

$logs = BiometricAttendanceLog::orderBy('timestamp')->get();
$inserted = 0;
$skipped_no_user = 0;
$skipped_exists = 0;

foreach ($logs as $log) {
    $user = User::where('biometric_id', $log->biometric_user_id)->first();

    if (!$user) {
        $skipped_no_user++;
        continue;
    }

    // Use biometric_id as punch_id if punch_id is not set
    $punchId = $user->punch_id ?: $user->biometric_id;

    try {
        $dateTime = \Carbon\Carbon::parse($log->timestamp)->format('Y-m-d H:i:s');
    } catch (\Exception $e) {
        echo "Failed to parse: {$log->timestamp}" . PHP_EOL;
        continue;
    }

    $record = AttMachineData::firstOrCreate(
        [
            'punch_id'  => $punchId,
            'date_time' => $dateTime,
        ],
        [
            'device_id' => $log->biometric_device_id,
        ]
    );

    if ($record->wasRecentlyCreated) {
        $inserted++;
    } else {
        $skipped_exists++;
    }
}

echo "✓ Inserted: {$inserted}" . PHP_EOL;
echo "✓ Already exists (skipped): {$skipped_exists}" . PHP_EOL;
echo "✗ No user mapping (skipped): {$skipped_no_user}" . PHP_EOL;
echo PHP_EOL . "att_machine_data total now: " . AttMachineData::count() . PHP_EOL;
echo PHP_EOL . "=== DONE ===" . PHP_EOL;
