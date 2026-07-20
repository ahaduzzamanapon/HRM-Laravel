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
        Schema::create('pension_eligibility_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('pension_profiles')->onDelete('cascade');
            $table->boolean('age_validated')->default(false);
            $table->boolean('service_years_validated')->default(false);
            $table->boolean('documents_verified')->default(false);
            $table->boolean('no_disciplinary_cases')->default(true);
            $table->text('lwp_impact_details')->nullable();
            $table->string('overall_status')->default('Pending'); // Pass, Fail, Pending
            $table->unsignedInteger('checked_by')->nullable();
            $table->foreign('checked_by')->references('id')->on('users');
            $table->timestamp('checked_at')->nullable();
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
        Schema::dropIfExists('pension_eligibility_checks');
    }
};
