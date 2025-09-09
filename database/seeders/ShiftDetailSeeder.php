<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShiftDetail;
use App\Models\Shift;

class ShiftDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $morningShift = Shift::where('shift_name', 'Morning Shift')->first();
        $eveningShift = Shift::where('shift_name', 'Evening Shift')->first();

        if (!$morningShift) {
            $morningShift = Shift::create([
                'shift_name' => 'Morning Shift',
                'branch_id' => \App\Models\Branch::first()->id,
            ]);
        }

        if (!$eveningShift) {
            $eveningShift = Shift::create([
                'shift_name' => 'Evening Shift',
                'branch_id' => \App\Models\Branch::first()->id,
            ]);
        }

        // Morning Shift Details
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Monday',
            'in_time' => '09:00:00',
            'out_time' => '17:00:00',
            'late_start_time' => '09:15:00',
            'lunch_start_time' => '12:00:00',
            'lunch_end_time' => '13:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Tuesday',
            'in_time' => '09:00:00',
            'out_time' => '17:00:00',
            'late_start_time' => '09:15:00',
            'lunch_start_time' => '12:00:00',
            'lunch_end_time' => '13:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Wednesday',
            'in_time' => '09:00:00',
            'out_time' => '17:00:00',
            'late_start_time' => '09:15:00',
            'lunch_start_time' => '12:00:00',
            'lunch_end_time' => '13:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Thursday',
            'in_time' => '09:00:00',
            'out_time' => '17:00:00',
            'late_start_time' => '09:15:00',
            'lunch_start_time' => '12:00:00',
            'lunch_end_time' => '13:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Friday',
            'in_time' => '09:00:00',
            'out_time' => '17:00:00',
            'late_start_time' => '09:15:00',
            'lunch_start_time' => '12:00:00',
            'lunch_end_time' => '13:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Saturday',
            'in_time' => null,
            'out_time' => null,
            'late_start_time' => null,
            'lunch_start_time' => null,
            'lunch_end_time' => null,
            'is_weekend' => true,
        ]);
        ShiftDetail::create([
            'shift_id' => $morningShift->id,
            'day_of_week' => 'Sunday',
            'in_time' => null,
            'out_time' => null,
            'late_start_time' => null,
            'lunch_start_time' => null,
            'lunch_end_time' => null,
            'is_weekend' => true,
        ]);

        // Evening Shift Details
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Monday',
            'in_time' => '17:00:00',
            'out_time' => '01:00:00',
            'late_start_time' => '17:15:00',
            'lunch_start_time' => '20:00:00',
            'lunch_end_time' => '21:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Tuesday',
            'in_time' => '17:00:00',
            'out_time' => '01:00:00',
            'late_start_time' => '17:15:00',
            'lunch_start_time' => '20:00:00',
            'lunch_end_time' => '21:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Wednesday',
            'in_time' => '17:00:00',
            'out_time' => '01:00:00',
            'late_start_time' => '17:15:00',
            'lunch_start_time' => '20:00:00',
            'lunch_end_time' => '21:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Thursday',
            'in_time' => '17:00:00',
            'out_time' => '01:00:00',
            'late_start_time' => '17:15:00',
            'lunch_start_time' => '20:00:00',
            'lunch_end_time' => '21:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Friday',
            'in_time' => '17:00:00',
            'out_time' => '01:00:00',
            'late_start_time' => '17:15:00',
            'lunch_start_time' => '20:00:00',
            'lunch_end_time' => '21:00:00',
            'is_weekend' => false,
        ]);
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Saturday',
            'in_time' => null,
            'out_time' => null,
            'late_start_time' => null,
            'lunch_start_time' => null,
            'lunch_end_time' => null,
            'is_weekend' => true,
        ]);
        ShiftDetail::create([
            'shift_id' => $eveningShift->id,
            'day_of_week' => 'Sunday',
            'in_time' => null,
            'out_time' => null,
            'late_start_time' => null,
            'lunch_start_time' => null,
            'lunch_end_time' => null,
            'is_weekend' => true,
        ]);
    }
}
