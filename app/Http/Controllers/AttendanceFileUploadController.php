<?php

namespace App\Http\Controllers;

use App\Models\AttMachineData;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Facades\Storage;

class AttendanceFileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendanceData = AttMachineData::paginate(10);
        return view('attendance_file_uploads.index')->with('attendanceData', $attendanceData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('attendance_file_uploads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendance_file' => 'required|mimes:txt|max:2048', // Validate as text file
        ]);

        if ($request->hasFile('attendance_file')) {
            $file = $request->file('attendance_file');
            $path = $file->getRealPath();
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Skip header line
            array_shift($lines);

            foreach ($lines as $line) {
                // Example line:    2  9/1/2025 9:35:14 AM           1                   FP
                // Columns: No. Date/Time Location ID ID Number VerifyCode CardNo
                $data = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);

                if (count($data) >= 4) { // Ensure enough columns are present
                    $punch_id = $data[0];
                    $date_time_str = $data[1] . ' ' . $data[2] . ' ' . $data[3]; // Combine date, time, AM/PM
                    $location_id = $data[4] ?? null; // Location ID

                    // Parse date_time_str into a valid datetime format
                    try {
                        $date_time = \Carbon\Carbon::parse($date_time_str)->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        // Log error or skip invalid date_time entries
                        \Log::error("Failed to parse date_time: {$date_time_str} for punch_id: {$punch_id}");
                        continue;
                    }

                    AttMachineData::create([
                        'punch_id' => $punch_id,
                        'date_time' => $date_time,
                        'device_id' => $location_id,
                    ]);
                }
            }

            Flash::success('Attendance file uploaded and processed successfully.');
        } else {
            Flash::error('No file uploaded.');
        }

        return redirect(route('attendanceFileUploads.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Not typically used for file uploads, but can show details of a specific upload batch if implemented
        return redirect(route('attendanceFileUploads.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Not typically used for file uploads
        return redirect(route('attendanceFileUploads.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Not typically used for file uploads
        return redirect(route('attendanceFileUploads.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Implement if you want to delete specific attendance records
        return redirect(route('attendanceFileUploads.index'));
    }
}
