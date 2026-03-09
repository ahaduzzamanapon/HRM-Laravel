<?php

namespace App\Http\Controllers;

use App\Models\BiometricAttendanceLog;
use Illuminate\Http\Request;

class BiometricAttendanceLogController extends Controller
{
    public function index(Request $request)
    {
        $query = BiometricAttendanceLog::with('device')->orderBy('timestamp', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('timestamp', $request->date);
        }

        $logs = $query->paginate(20);

        // Pre-fetch related users based on biometric_id
        $bioIds = $logs->pluck('biometric_user_id')->unique();
        $users = \App\Models\User::whereIn('biometric_id', $bioIds)->get()->keyBy('biometric_id');

        return view('biometric_attendance_logs.index', compact('logs', 'users'));
    }
}
