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
        Schema::create('training_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // Changed from foreignId
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Manually define foreign key
            $table->string('training_name');
            $table->string('training_provider');
            $table->string('training_type'); // Domestic/Foreign
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->string('document')->nullable(); // For uploading training certificates
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
        Schema::dropIfExists('training_details');
    }
};
