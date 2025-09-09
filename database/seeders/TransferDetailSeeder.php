<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransferDetail;
use App\Models\User;

class TransferDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user, or create one if none exists

        if (!$user) {
            $user = User::factory()->create();
        }

        TransferDetail::create([
            'user_id' => $user->id,
            'transfer_date' => '2021-08-01',
            'old_branch' => 'Main Branch',
            'new_branch' => 'City Branch',
            'reason' => 'Company expansion',
            'status' => 'Approved',
            'document' => null,
        ]);

        TransferDetail::create([
            'user_id' => $user->id,
            'transfer_date' => '2023-02-10',
            'old_branch' => 'City Branch',
            'new_branch' => 'Head Office',
            'reason' => 'Promotion',
            'status' => 'Approved',
            'document' => null,
        ]);
    }
}
