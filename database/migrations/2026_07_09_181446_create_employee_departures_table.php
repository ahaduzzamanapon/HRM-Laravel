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
        Schema::create('employee_departures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('status'); // 'left', 'resign', 'retired'
            $table->date('effective_date');
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('employee_departures');
    }
};
