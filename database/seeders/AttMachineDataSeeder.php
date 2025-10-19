<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AttMachineData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Services\AttendanceService;


class AttMachineDataSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $attendanceService = app(AttendanceService::class);
        $users = User::all();
        $date = Carbon::now();

        foreach ($users as $user) {

            // Simulate a check-in
            AttMachineData::create([
                'punch_id' => $user->punch_id,
                'date_time' => $date->copy()->setTime(9, rand(0, 15), rand(0, 59)),
            ]);

            // Simulate a check-out
            AttMachineData::create([
                'punch_id' => $user->punch_id,
                'date_time' => $date->copy()->setTime(17, rand(0, 15), rand(0, 59)),
            ]);

            $attendanceService->attn_process($date->format('Y-m-d'),  $user->id);
        }
    }
}
