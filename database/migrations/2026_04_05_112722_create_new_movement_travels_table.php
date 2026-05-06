<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('new_movement_travels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movement_id');
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('start_lat')->nullable();
            $table->string('start_lng')->nullable();
            $table->string('end_lat')->nullable();
            $table->string('end_lng')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->decimal('distance', 8, 2)->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->string('status')->default('running');
            $table->boolean('is_office_return')->default(0);
            $table->timestamps();

            $table->foreign('movement_id')->references('id')->on('new_movement_movements')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_movement_travels');
    }
};
