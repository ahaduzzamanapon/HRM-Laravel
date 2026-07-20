<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('billing_period'); // e.g., '2026-07'
            $table->decimal('base_amount', 15, 2);
            $table->decimal('arrear_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->timestamps();

            // Enforce unique billing periods per user
            $table->unique(['user_id', 'billing_period']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->timestamp('paid_at')->useCurrent();
            $table->timestamps();
        });

        // Insert permission keys and assign them to Admin
        $pensionPermission = DB::table('permissions')->where('key', 'pension')->first();
        $pensionId = $pensionPermission ? $pensionPermission->id : null;
        if (!$pensionPermission) {
            $pensionId = DB::table('permissions')->insertGetId([
                'name' => 'Pension',
                'key' => 'pension',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $arrearBillPermissionId = DB::table('permissions')->insertGetId([
            'name' => 'Manage Arrear Bills',
            'key' => 'manage_arrear_bills',
            'parent_id' => $pensionId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        if ($adminRole) {
            DB::table('roll_has')->insertOrIgnore([
                'roll_id' => $adminRole->id,
                'permission_id' => $arrearBillPermissionId,
            ]);
            if ($pensionId) {
                DB::table('roll_has')->insertOrIgnore([
                    'roll_id' => $adminRole->id,
                    'permission_id' => $pensionId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete permissions first
        $permissionId = DB::table('permissions')->where('key', 'manage_arrear_bills')->value('id');
        if ($permissionId) {
            DB::table('roll_has')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        Schema::dropIfExists('payments');
        Schema::dropIfExists('bills');
    }
};
