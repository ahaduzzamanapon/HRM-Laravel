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
        Schema::create('pension_workflows', function (Blueprint $table) {
            $table->id();
            $table->morphs('trackable'); // Can track Profile, Calculation, or Disbursement
            $table->string('stage_name'); // Maker, Checker, Committee, Final Approval
            $table->string('status'); // Pending, Approved, Rejected, Returned
            $table->text('remarks')->nullable();
            $table->unsignedInteger('action_by')->nullable();
            $table->foreign('action_by')->references('id')->on('users');
            $table->timestamp('action_at')->nullable();
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
        Schema::dropIfExists('pension_workflows');
    }
};
