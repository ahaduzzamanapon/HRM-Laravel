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
            $table->string('title');
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->decimal('tax_yearly', 10, 2)->nullable();
            $table->decimal('tax_monthly', 10, 2)->nullable();
            $table->string('updated_by')->nullable();
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
