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
        Schema::table('child_allowances', function (Blueprint $table) {
            $table->date('start_month')->change();
            $table->date('end_month')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('child_allowances', function (Blueprint $table) {
            $table->integer('start_month')->change();
            $table->integer('end_month')->change();
        });
    }
};
