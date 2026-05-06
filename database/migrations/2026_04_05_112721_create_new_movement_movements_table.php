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
        Schema::create('new_movement_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->string('start_location')->nullable();
            $table->string('start_latitude')->nullable();
            $table->string('start_longitude')->nullable();
            $table->string('purpose')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('start_photo')->nullable();
            $table->string('status')->default('active');
            $table->string('ta_status')->default('not_applied');
            $table->decimal('ta_amount', 10, 2)->default(0);
            $table->decimal('ta_app_amt', 10, 2)->default(0);
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_movement_movements');
    }
};
