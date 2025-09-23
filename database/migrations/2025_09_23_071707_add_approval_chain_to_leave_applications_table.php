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
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedInteger('approver_id')->nullable()->after('status');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
            $table->string('approver_level')->nullable()->after('approver_id');
            $table->unsignedInteger('final_approver_id')->nullable()->after('approver_level');
            $table->foreign('final_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropForeign(['final_approver_id']);
            $table->dropColumn(['approver_id', 'approver_level', 'final_approver_id']);
        });
    }
};