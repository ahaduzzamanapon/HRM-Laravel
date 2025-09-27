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
        Schema::table('salary_increments', function (Blueprint $table) {
            $table->integer('old_grade_id')->nullable();
            $table->integer('new_grade_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_increments', function (Blueprint $table) {
            $table->dropColumn('old_grade_id');
            $table->dropColumn('new_grade_id');
        });
    }
};
