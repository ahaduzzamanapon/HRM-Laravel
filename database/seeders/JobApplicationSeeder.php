<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use App\Models\Recruitment;
use Illuminate\Support\Facades\Storage;

class JobApplicationSeeder extends Seeder
{
    public function run()
    {
        $jobs = Recruitment::where('status', 'published')->get();
        if ($jobs->isEmpty()) {
            return;
        }

        $candidates = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@example.com', 'phone' => '+1234567890'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@example.com', 'phone' => '+1987654321'],
            ['first_name' => 'Michael', 'last_name' => 'Johnson', 'email' => 'michael.j@example.com', 'phone' => '+1122334455'],
            ['first_name' => 'Emily', 'last_name' => 'Davis', 'email' => 'emily.davis@example.com', 'phone' => '+1555666777'],
            ['first_name' => 'David', 'last_name' => 'Brown', 'email' => 'david.brown@example.com', 'phone' => '+1444555666'],
            ['first_name' => 'Sarah', 'last_name' => 'Miller', 'email' => 'sarah.miller@example.com', 'phone' => '+1777888999'],
            ['first_name' => 'James', 'last_name' => 'Wilson', 'email' => 'james.wilson@example.com', 'phone' => '+1888999000'],
            ['first_name' => 'Linda', 'last_name' => 'Moore', 'email' => 'linda.moore@example.com', 'phone' => '+1999000111'],
            ['first_name' => 'Robert', 'last_name' => 'Taylor', 'email' => 'robert.taylor@example.com', 'phone' => '+1000111222'],
            ['first_name' => 'William', 'last_name' => 'Anderson', 'email' => 'william.anderson@example.com', 'phone' => '+1222333444'],
        ];

        // A minimal valid PDF encoded in base64
        $pdfBase64 = 'JVBERi0xLjEKJcKlwrHDqwoKMSAwIG9iagogIDw8IC9UeXBlIC9DYXRhbG9nCiAgICAgL1BhZ2VzIDIgMCBSCiAgPj4KZW5kb2JqCgoyIDAgb2JqCiAgPDwgL1R5cGUgL1BhZ2VzCiAgICAgL0tpZHMgWzMgMCBSXQogICAgIC9Db3VudCAxCiAgICAgL01lZGlhQm94IFswIDAgMzAwIDE0NF0KICA+PgplbmRvYmoKCjMgMCBvYmoKICA8PCAgL1R5cGUgL1BhZ2UKICAgICAgL1BhcmVudCAyIDAgUgogICAgICAvUmVzb3VyY2VzCiAgICAgICA8PCAvRm9udAogICAgICAgICAgIDw8IC9GMQogICAgICAgICAgICAgICA8PCAvVHlwZSAvRm9udAogICAgICAgICAgICAgICAgICAvU3VidHlwZSAvVHlwZTEKICAgICAgICAgICAgICAgICAgL0Jhc2VGb250IC9UaW1lcy1Sb21hbgogICAgICAgICAgICAgICA+PgogICAgICAgICAgID4+CiAgICAgICA+PgogICAgICAvQ29udGVudHMgNCAwIFIKICA+PgplbmRvYmoKCjQgMCBvYmoKICA8PCAvTGVuZ3RoIDY1ID4+CnN0cmVhbQogIEJUCiAgICAvRjEgMTggVGYKICAgIDAgMCAwIHJnCiAgICAxMCAxMDAgVGQKICAgIChDViBmb3IgQ2FuZGlkYXRlKSBUagogIEVUCmVuZHN0cmVhbQplbmRvYmoKCnhyZWYKMCA1CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDAxOCAwMDAwMCBuIAowMDAwMDAwMDc3IDAwMDAwIG4gCjAwMDAwMDAxNzggMDAwMDAgbiAKMDAwMDAwMDQ1NyAwMDAwMCBuIAp0cmFpbGVyCiAgPDwgIC9Sb290IDEgMCBSCiAgICAgIC9TaXplIDUKICA+PgpzdGFydHhyZWYKNTcyCiUlRU9GCg==';
        $pdfContent = base64_decode($pdfBase64);

        foreach ($candidates as $candidate) {
            $job = $jobs->random();
            $fileName = 'resumes/dummy_resume_' . $candidate['first_name'] . '_' . time() . '.pdf';
            
            Storage::disk('local')->put($fileName, $pdfContent);
            
            JobApplication::create([
                'recruitment_id' => $job->id,
                'first_name' => $candidate['first_name'],
                'last_name' => $candidate['last_name'],
                'email' => $candidate['email'],
                'phone' => $candidate['phone'],
                'cover_letter' => 'I am very interested in the ' . $job->title . ' position. I believe my skills and experience make me a strong candidate for this role.',
                'resume_path' => $fileName,
                'status' => 'new'
            ]);
        }
    }
}
