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
        Schema::create('att_machine_data', function (Blueprint $table) {
            $table->id();
            $table->string('punch_id');
            $table->dateTime('date_time');
            $table->string('device_id')->nullable(); // Assuming Location ID maps to device_id
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
        Schema::dropIfExists('att_machine_data');
    }
};
