<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('new_movement_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movement_id');
            $table->string('entity_type')->nullable();
            $table->string('client_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_job_title')->nullable();
            $table->string('meeting_type')->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->text('feedback')->nullable();
            $table->string('crm_lead_id')->nullable();
            $table->timestamps();

            $table->foreign('movement_id')->references('id')->on('new_movement_movements')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_movement_meetings');
    }
};
