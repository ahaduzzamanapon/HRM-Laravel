<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ShiftDetail;
use App\Models\BiometricAttendanceLog;
use Carbon\Carbon;

echo "=== BUG CHECK REPORT ===" . PHP_EOL . PHP_EOL;

// Bug 1: Users with no shift_id
echo "[BUG CHECK 1] Users with NO shift_id:" . PHP_EOL;
$noShift = User::where('group_id', '!=', 1)->whereNull('shift_id')->orWhere('shift_id', 0)->get(['id','name','shift_id']);
echo "Count: " . $noShift->count() . PHP_EOL;
$noShift->each(fn($u) => print("  user_id:{$u->id} | {$u->name}" . PHP_EOL));

echo PHP_EOL;

// Bug 2: Users with shift_id but shift has no ShiftDetails
echo "[BUG CHECK 2] Users whose shift has NO ShiftDetail records:" . PHP_EOL;
$usersWithShift = User::where('group_id', '!=', 1)->whereNotNull('shift_id')->where('shift_id', '!=', 0)->get(['id','name','shift_id']);
$missingShiftDetail = [];
foreach ($usersWithShift as $u) {
    $count = ShiftDetail::where('shift_id', $u->shift_id)->count();
    if ($count == 0) {
        $missingShiftDetail[] = "  user_id:{$u->id} | {$u->name} | shift_id:{$u->shift_id}";
    }
}
echo "Count: " . count($missingShiftDetail) . PHP_EOL;
foreach ($missingShiftDetail as $line) echo $line . PHP_EOL;

echo PHP_EOL;

// Bug 3: ShiftDetails missing specific days (e.g., today's day of week)
$today = Carbon::now()->format('l');
echo "[BUG CHECK 3] ShiftDetails missing for today ({$today}):" . PHP_EOL;
$allShiftIds = ShiftDetail::distinct()->pluck('shift_id');
foreach ($allShiftIds as $sid) {
    $hasTodayRow = ShiftDetail::where('shift_id', $sid)->where('day_of_week', $today)->exists();
    if (!$hasTodayRow) {
        echo "  shift_id:{$sid} has NO detail for {$today}" . PHP_EOL;
    }
}

echo PHP_EOL;

// Bug 4: biometric_id in logs but no user mapped
echo "[BUG CHECK 4] Biometric IDs in logs with NO matching user:" . PHP_EOL;
$logIds = BiometricAttendanceLog::distinct()->pluck('biometric_user_id')->toArray();
$matchedIds = User::whereIn('biometric_id', $logIds)->pluck('biometric_id')->toArray();
$unmatched = array_diff($logIds, $matchedIds);
echo "Unmatched count: " . count($unmatched) . " IDs: " . implode(', ', array_slice($unmatched, 0, 20)) . PHP_EOL;

echo PHP_EOL;

// Bug 5: Users with biometric_id but no biometric log AT ALL (last 30 days)
echo "[BUG CHECK 5] Users with biometric_id but ZERO log entries (last 30 days):" . PHP_EOL;
$from = Carbon::now()->subDays(30)->format('Y-m-d');
$usersWithBioId = User::where('group_id', '!=', 1)->whereNotNull('biometric_id')->get(['id','name','biometric_id']);
$zeroLog = [];
foreach ($usersWithBioId as $u) {
    $cnt = BiometricAttendanceLog::where('biometric_user_id', $u->biometric_id)
        ->whereDate('timestamp', '>=', $from)->count();
    if ($cnt == 0) {
        $zeroLog[] = "  user_id:{$u->id} | {$u->name} | biometric_id:{$u->biometric_id}";
    }
}
echo "Count: " . count($zeroLog) . PHP_EOL;
foreach ($zeroLog as $l) echo $l . PHP_EOL;

echo PHP_EOL;

// Bug 6: Users with no biometric_id AND no punch_id (can NEVER get attendance)
echo "[BUG CHECK 6] Users with NEITHER biometric_id NOR punch_id (attendance impossible):" . PHP_EOL;
$noBoth = User::where('group_id', '!=', 1)
    ->whereNull('biometric_id')
    ->whereNull('punch_id')
    ->get(['id','name','biometric_id','punch_id']);
echo "Count: " . $noBoth->count() . PHP_EOL;
$noBoth->each(fn($u) => print("  user_id:{$u->id} | {$u->name}" . PHP_EOL));

echo PHP_EOL;

// Bug 7: leave_check uses ->get() then checks $query[0] - inefficient but functional
echo "[BUG CHECK 7] leave_check() uses ->get() + \$query[0] instead of ->first() — minor inefficiency, not a functional bug." . PHP_EOL;

echo PHP_EOL;

// Bug 8: holiday_check - Holyday casts 'date' as 'date' type, comparing with string
echo "[BUG CHECK 8] holiday_check: Holyday model casts 'date' as Carbon object, but compared with plain string." . PHP_EOL;
echo "  This may cause '0 rows found' even when holiday exists. Test: " . PHP_EOL;
$testDate = Carbon::today()->format('Y-m-d');
$h = \App\Models\Holyday::where('date', $testDate)->where('status','Published')->first();
echo "  Today's holiday query for date={$testDate}: " . ($h ? "FOUND - {$h->title}" : "none") . PHP_EOL;

echo PHP_EOL;

// Bug 9: getReportData - when reportType=daily and filterType=leave — date filter is NOT applied!
echo "[BUG CHECK 9] getReportData() — when filterType='leave', attendance_date filter is MISSING." . PHP_EOL;
echo "  Result: 'leave' filter returns ALL leave records across ALL dates, not just the selected date." . PHP_EOL;

echo PHP_EOL;

// Bug 10: attendance_status 'Continue' logic
echo "[BUG CHECK 10] Status 'Continue' — when employee only punched once (single timestamp):" . PHP_EOL;
echo "  - in_time is set, out_time is null => status='Continue', attendance_status='Continue'" . PHP_EOL;
echo "  - But NEXT DAY re-process: if still only 1 punch, it stays 'Continue' forever." . PHP_EOL;
echo "  - No mechanism to auto-finalize 'Continue' to 'Present' or 'Absent' after shift ends." . PHP_EOL;

echo PHP_EOL . "=== END OF BUG REPORT ===" . PHP_EOL;
