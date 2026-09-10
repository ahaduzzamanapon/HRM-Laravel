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
        if (!Schema::hasTable('welfare_support_attachments')) {
            Schema::create('welfare_support_attachments', function (Blueprint $table) {
                $table->id();
                $table->string('support_type', 50); // medical, funeral, education
                $table->unsignedBigInteger('support_id');
                $table->string('file_path');
                $table->string('original_name');
                $table->string('mime_type', 100)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->integer('uploaded_by')->unsigned();
                $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();

                $table->index(['support_type', 'support_id']);
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
        Schema::dropIfExists('welfare_support_attachments');
    }
};
