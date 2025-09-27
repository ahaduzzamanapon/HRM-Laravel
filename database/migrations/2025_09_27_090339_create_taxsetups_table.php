<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxsetupsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxsetups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titel');
            $table->integer('min_salary');
            $table->integer('max_salary');
            $table->integer('tax_yearly');
            $table->integer('tax_monthly');
            $table->string('update_by');
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
        Schema::drop('taxsetups');
    }
}
