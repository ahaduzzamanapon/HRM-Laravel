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
    public function up(): void
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->longText('description');
            $table->string('location')->nullable();
            $table->string('employment_type')->default('Full-time'); 
            $table->string('experience_level')->nullable();
            $table->decimal('salary_range_start', 10, 2)->nullable();
            $table->decimal('salary_range_end', 10, 2)->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
