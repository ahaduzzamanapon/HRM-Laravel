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
        Schema::create('departmental_cases', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('allegation_type');
            $table->string('allegation_category');
            $table->text('disciplinary_issue_details');
            $table->text('committee_comments')->nullable();
            $table->unsignedBigInteger('penalty_id')->nullable();
            $table->foreign('penalty_id')->references('id')->on('penalties')->onDelete('set null');
            $table->text('final_action_taken')->nullable();
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
        Schema::dropIfExists('departmental_cases');
    }
};
