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
        Schema::create('funeral_supports', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->date('support_date');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('funeral_supports');
    }
};