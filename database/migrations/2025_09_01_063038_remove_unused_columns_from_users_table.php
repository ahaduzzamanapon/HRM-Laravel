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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'salary',
                'nid',
                'education',
                'punch_id',
                'experience',
                'email_verified_at',
                'remember_token',
            ]);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('salary')->nullable();
            $table->string('nid')->nullable();
            $table->string('education')->nullable();
            $table->string('punch_id')->nullable();
            $table->string('experience')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
        });
    }
};
