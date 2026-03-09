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
        Schema::create('biometric_commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biometric_device_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable(); // Mapped user
            $table->string('command_type'); // e.g., CREATEUSER
            $table->text('command_string'); // The exact raw string to send
            $table->enum('status', ['pending', 'sent', 'executed', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('biometric_commands');
    }
};
