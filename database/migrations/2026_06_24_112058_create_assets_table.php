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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->date('warranty_expiry_date')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('status', ['Available', 'Assigned', 'Maintenance', 'Retired', 'Disposed'])->default('Available');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('assets');
    }
};
