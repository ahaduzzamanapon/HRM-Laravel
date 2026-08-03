<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pf_approval_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // E.g., App\Models\PfWithdrawal
            $table->unsignedBigInteger('model_id');
            $table->integer('approver_id')->unsigned();
            $table->integer('level')->default(1);
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pf_approval_workflows');
    }
};
