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
        Schema::dropIfExists('maintenance_requests');
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('maintenance_types')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            
            $table->integer('requested_by')->unsigned();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', ['Pending', 'Assigned', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            $table->date('requested_date');
            $table->date('scheduled_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('warranty_expiry_date')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('maintenance_requests');
    }
};
