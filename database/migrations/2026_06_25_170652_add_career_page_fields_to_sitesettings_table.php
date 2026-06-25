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
        Schema::table('sitesettings', function (Blueprint $table) {
            $table->string('career_title')->nullable()->default('Discover Your Future');
            $table->string('career_subtitle')->nullable()->default('Build a rewarding career with an institution committed to excellence, integrity, and growth.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sitesettings', function (Blueprint $table) {
            $table->dropColumn(['career_title', 'career_subtitle']);
        });
    }
};
