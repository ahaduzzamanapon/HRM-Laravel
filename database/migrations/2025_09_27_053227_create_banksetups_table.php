<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBanksetupsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banksetups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('address');
            $table->string('bank_code');
            $table->text('description');
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
        Schema::drop('banksetups');
    }
}
