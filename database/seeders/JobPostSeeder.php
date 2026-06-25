<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recruitment;
use Illuminate\Support\Str;

class JobPostSeeder extends Seeder
{
    public function run()
    {
        $jobTitles = [
            'Branch Manager', 'Credit Analyst', 'Investment Banker', 'Loan Officer', 
            'Financial Advisor', 'Teller', 'Risk Manager', 'Compliance Officer',
            'Wealth Manager', 'Corporate Banker', 'Retail Banking Manager',
            'Mortgage Advisor', 'Quantitative Analyst', 'Treasury Analyst',
            'Fraud Investigator', 'Audit Manager', 'Private Banker',
            'Customer Relationship Manager', 'Operations Manager', 'Fintech Specialist'
        ];

        $locations = ['New York, NY', 'Remote', 'London, UK', 'San Francisco, CA', 'Chicago, IL', 'Toronto, ON', 'Frankfurt, Germany', 'Singapore'];
        $employmentTypes = ['Full-time', 'Part-time', 'Contract'];
        $experienceLevels = ['Entry Level', 'Mid Level', 'Senior Level', 'Executive'];

        $imageUrls = [
            'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=800&q=80'
        ];

        foreach ($jobTitles as $index => $title) {
            $image = $imageUrls[array_rand($imageUrls)];
            
            $description = '
                <h3>About the Role</h3>
                <p>We are seeking a highly motivated and experienced <strong>' . $title . '</strong> to join our dynamic banking team. The ideal candidate will have a strong background in financial services and a proven track record of delivering exceptional results.</p>
                <img src="' . $image . '" alt="Banking Environment" style="max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0;">
                
                <h3>Key Responsibilities</h3>
                <ul>
                    <li>Manage and oversee daily banking operations and client portfolios.</li>
                    <li>Ensure compliance with all financial regulations and internal policies.</li>
                    <li>Develop and implement strategies to drive business growth.</li>
                    <li>Provide outstanding financial advice and service to our clients.</li>
                </ul>

                <h3>Qualifications</h3>
                <ul>
                    <li>Bachelor’s degree in Finance, Business, or a related field.</li>
                    <li>3+ years of experience in the banking or financial sector.</li>
                    <li>Strong analytical, communication, and leadership skills.</li>
                </ul>

                <p>For more information about our corporate culture and benefits, please visit our <a href="https://example-bank.com/careers" target="_blank" style="color: #007bff; text-decoration: none; font-weight: bold;">Corporate Careers Page</a>.</p>
            ';

            Recruitment::create([
                'title' => $title,
                'slug' => Str::slug($title . '-' . time() . '-' . $index),
                'description' => trim($description),
                'location' => $locations[array_rand($locations)],
                'employment_type' => $employmentTypes[array_rand($employmentTypes)],
                'experience_level' => $experienceLevels[array_rand($experienceLevels)],
                'salary_range_start' => rand(50000, 90000),
                'salary_range_end' => rand(95000, 180000),
                'deadline' => now()->addDays(rand(10, 60)),
                'status' => 'published',
            ]);
        }
    }
}
