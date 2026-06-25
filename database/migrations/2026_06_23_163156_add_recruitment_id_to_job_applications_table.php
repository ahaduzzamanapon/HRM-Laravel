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
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'recruitment_id')) {
                $table->foreignId('recruitment_id')->nullable()->constrained('recruitments')->cascadeOnDelete();
            }
            // change may fail if it's already nullable, but should be fine
            $table->foreignId('job_post_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['recruitment_id']);
            $table->dropColumn('recruitment_id');
            $table->foreignId('job_post_id')->nullable(false)->change();
        });
    }
};
