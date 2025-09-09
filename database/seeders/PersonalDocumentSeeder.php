<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonalDocument;
use App\Models\User;

class PersonalDocumentSeeder extends Seeder
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

        PersonalDocument::create([
            'user_id' => $user->id,
            'document_type' => 'Passport',
            'document_file' => 'documents/personal/passport_placeholder.pdf',
            'description' => 'Employee\'s valid passport copy.',
        ]);

        PersonalDocument::create([
            'user_id' => $user->id,
            'document_type' => 'National ID',
            'document_file' => 'documents/personal/national_id_placeholder.pdf',
            'description' => 'Employee\'s national ID card copy.',
        ]);
    }
}
