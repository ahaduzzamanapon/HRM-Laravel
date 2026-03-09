<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biometric_attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biometric_device_id')->constrained()->onDelete('cascade');
            $table->string('biometric_user_id'); // String because some devices use alphanumeric IDs
            $table->dateTime('timestamp');
            $table->integer('status_code')->nullable(); // punch state, e.g., 0 for check-in, 1 for check-out
            $table->integer('verify_mode')->nullable(); // e.g., fingerprint, password
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
        Schema::dropIfExists('biometric_attendance_logs');
    }
};
