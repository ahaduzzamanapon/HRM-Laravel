<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AttMachineData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttMachineDataSeeder extends Seeder
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
            }
        }
    }
}
