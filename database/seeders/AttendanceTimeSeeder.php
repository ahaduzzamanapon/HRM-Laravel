<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AttendanceTime;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(2);

        foreach ($users as $user) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $checkIn = $date->copy()->setTime(9, rand(0, 15), rand(0, 59));
                $checkOut = $date->copy()->setTime(17, rand(0, 15), rand(0, 59));

                AttendanceTime::create([
                    'employee_id' => $user->id,
                    'attendance_date' => $date->format('Y-m-d'),
                    'clock_in' => $checkIn->format('H:i:s'),
                    'clock_out' => $checkOut->format('H:i:s'),
                    'total_work' => $checkOut->diffInHours($checkIn),
                ]);
            }
        }
    }
}
