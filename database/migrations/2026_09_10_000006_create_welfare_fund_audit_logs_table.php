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
        if (!Schema::hasTable('welfare_fund_audit_logs')) {
            Schema::create('welfare_fund_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->string('action', 100);
                $table->string('support_type', 50)->nullable();
                $table->unsignedBigInteger('support_id')->nullable();
                $table->string('old_status', 50)->nullable();
                $table->string('new_status', 50)->nullable();
                $table->text('remarks')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_fund_audit_logs');
    }
};
