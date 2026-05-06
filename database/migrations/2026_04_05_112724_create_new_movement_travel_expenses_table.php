<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('new_movement_travel_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movement_id');
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->string('transport_type')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('approve_amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('movement_id')->references('id')->on('new_movement_movements')->onDelete('cascade');
            $table->foreign('travel_id')->references('id')->on('new_movement_travels')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_movement_travel_expenses');
    }
};
