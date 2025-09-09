<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_time', function (Blueprint $table) {
            $table->increments('time_attendance_id');
            $table->integer('employee_id');
            $table->integer('office_shift_id');
            $table->date('attendance_date');
            $table->string('clock_in');
            $table->string('clock_out');
            $table->string('lunch_in', 32);
            $table->string('lunch_out', 32);
            $table->tinyInteger('late_status');
            $table->smallInteger('production')->default(0);
            $table->smallInteger('ot')->default(0);
            $table->smallInteger('late_time')->default(0);
            $table->tinyInteger('move_status')->default(0)->comment('1=move_leave, 0=no_leave');
            $table->tinyInteger('lunch_late_status')->default(0);
            $table->tinyInteger('early_out_status')->default(0);
            $table->string('clock_in_ip_address');
            $table->string('clock_out_ip_address');
            $table->string('clock_in_out');
            $table->text('comment');
            $table->string('clock_in_latitude', 150);
            $table->string('clock_in_longitude', 150);
            $table->string('clock_out_latitude', 150);
            $table->string('clock_out_longitude', 150);
            $table->string('time_late');
            $table->string('early_leaving');
            $table->string('overtime');
            $table->string('total_work');
            $table->string('total_rest');
            $table->string('attendance_status', 100);
            $table->string('status', 24);
            $table->tinyInteger('extra_ap')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_time');
    }
};