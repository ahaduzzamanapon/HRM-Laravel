<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE loans MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('Pending', 'Approved', 'Disbursed', 'Repaid', 'Rejected') NOT NULL DEFAULT 'Pending'");
    }
};
