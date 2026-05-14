<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BiometricAttendanceLog;
use App\Models\AttMachineData;
use App\Models\User;

echo "=== BiometricAttendanceLog count ===" . PHP_EOL;
echo BiometricAttendanceLog::count() . PHP_EOL;

echo PHP_EOL . "=== Latest 5 BiometricAttendanceLogs ===" . PHP_EOL;
BiometricAttendanceLog::latest('timestamp')->take(5)->get(['biometric_user_id', 'timestamp'])->each(function ($r) {
    echo $r->biometric_user_id . ' | ' . $r->timestamp . PHP_EOL;
});

echo PHP_EOL . "=== AttMachineData count ===" . PHP_EOL;
echo AttMachineData::count() . PHP_EOL;

echo PHP_EOL . "=== Latest 5 AttMachineData ===" . PHP_EOL;
AttMachineData::latest('date_time')->take(5)->get(['punch_id', 'date_time'])->each(function ($r) {
    echo $r->punch_id . ' | ' . $r->date_time . PHP_EOL;
});

echo PHP_EOL . "=== Users with biometric_id set ===" . PHP_EOL;
$bioCount = User::whereNotNull('biometric_id')->count();
echo $bioCount . ' users have biometric_id' . PHP_EOL;

echo PHP_EOL . "=== Users with punch_id set ===" . PHP_EOL;
$punchCount = User::whereNotNull('punch_id')->count();
echo $punchCount . ' users have punch_id' . PHP_EOL;

echo PHP_EOL . "=== Sample: biometric_id vs punch_id (first 5 users) ===" . PHP_EOL;
User::whereNotNull('biometric_id')->take(5)->get(['id', 'name', 'biometric_id', 'punch_id'])->each(function ($r) {
    echo "user_id:{$r->id} | name:{$r->name} | biometric_id:{$r->biometric_id} | punch_id:{$r->punch_id}" . PHP_EOL;
});

echo PHP_EOL . "=== BiometricAttendanceLog: biometric_user_ids that HAVE no matching User.biometric_id ===" . PHP_EOL;
$logIds = BiometricAttendanceLog::distinct()->pluck('biometric_user_id')->toArray();
$matchedIds = User::whereIn('biometric_id', $logIds)->pluck('biometric_id')->toArray();
$unmatchedIds = array_diff($logIds, $matchedIds);
echo "Total unique biometric IDs in log: " . count($logIds) . PHP_EOL;
echo "Matched to users: " . count($matchedIds) . PHP_EOL;
echo "Unmatched (no user mapping): " . count($unmatchedIds) . PHP_EOL;
if (!empty($unmatchedIds)) {
    echo "Unmatched IDs: " . implode(', ', array_slice($unmatchedIds, 0, 10)) . PHP_EOL;
}

echo PHP_EOL . "=== Users with biometric_id but NO punch_id ===" . PHP_EOL;
User::whereNotNull('biometric_id')->whereNull('punch_id')->get(['id','name','biometric_id','punch_id'])->each(function($r){
    echo "user_id:{$r->id} | name:{$r->name} | biometric_id:{$r->biometric_id} | punch_id: NULL" . PHP_EOL;
});

echo PHP_EOL . "=== DONE ===" . PHP_EOL;
