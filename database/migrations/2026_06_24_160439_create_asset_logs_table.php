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
        Schema::create('asset_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->string('event_type'); // Assigned, Returned, Maintenance, Disposed, Registered, Note
            $table->unsignedBigInteger('user_id')->nullable(); // Employee involved
            $table->unsignedBigInteger('department_id')->nullable(); // Department involved
            $table->unsignedBigInteger('action_by')->nullable(); // User who did the action
            $table->text('notes')->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->nullableMorphs('reference'); // polymorphic: reference_type, reference_id
            $table->timestamps();
            
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_logs');
    }
};
