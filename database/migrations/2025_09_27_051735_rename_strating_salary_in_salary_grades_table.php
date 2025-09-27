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
        Schema::table('salary_grades', function (Blueprint $table) {
            $table->renameColumn('strating_salary', 'starting_salary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_grades', function (Blueprint $table) {
            $table->renameColumn('starting_salary', 'strating_salary');
        });
    }
};
