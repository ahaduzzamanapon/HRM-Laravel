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
        Schema::table('new_movement_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('new_movement_movements', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('ta_app_amt');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_movement_movements', function (Blueprint $table) {
            if (Schema::hasColumn('new_movement_movements', 'admin_note')) {
                $table->dropColumn('admin_note');
            }
        });
    }
};
