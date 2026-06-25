<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('user_id'); // employee
            $table->date('assigned_date');
            $table->date('expected_return_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('condition_on_assignment')->nullable();
            $table->string('condition_on_return')->nullable();
            $table->enum('status', ['Assigned', 'Returned'])->default('Assigned');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('assigned_by');
            $table->unsignedBigInteger('returned_to')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_assignments');
    }
};
