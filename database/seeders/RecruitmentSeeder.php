<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recruitment;
use Illuminate\Support\Str;

class RecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobs = [
            [
                'title' => 'Senior Full Stack Developer',
                'description' => "We are looking for an experienced Full Stack Developer to help us build scalable web applications. \n\nRequirements:\n- 5+ years of experience in PHP (Laravel) and Vue.js/React.\n- Strong understanding of SQL databases.\n- Experience with Docker and CI/CD pipelines.\n\nBenefits:\n- Flexible working hours.\n- Health insurance.\n- Remote work opportunities.",
                'location' => 'New York, USA (Hybrid)',
                'employment_type' => 'Full-time',
                'experience_level' => 'Senior',
                'salary_range_start' => 90000.00,
                'salary_range_end' => 130000.00,
                'deadline' => now()->addDays(30),
                'status' => 'published',
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => "We are seeking a creative UI/UX Designer to craft beautiful and intuitive user experiences.\n\nResponsibilities:\n- Design user interfaces for our web and mobile apps.\n- Create wireframes, prototypes, and high-fidelity mockups.\n- Collaborate with developers to ensure accurate implementation.\n\nRequirements:\n- 3+ years of experience in UI/UX design.\n- Proficiency in Figma or Adobe XD.\n- A strong portfolio showcasing modern design skills.",
                'location' => 'Remote',
                'employment_type' => 'Contract',
                'experience_level' => 'Mid',
                'salary_range_start' => 60000.00,
                'salary_range_end' => 85000.00,
                'deadline' => now()->addDays(15),
                'status' => 'published',
            ],
            [
                'title' => 'Digital Marketing Specialist',
                'description' => "Join our marketing team to drive growth and brand awareness!\n\nResponsibilities:\n- Manage social media campaigns.\n- SEO and SEM optimization.\n- Content creation and strategy.\n\nRequirements:\n- 2+ years in digital marketing.\n- Experience with Google Analytics and Ads.\n- Excellent communication skills.",
                'location' => 'London, UK',
                'employment_type' => 'Full-time',
                'experience_level' => 'Entry',
                'salary_range_start' => 45000.00,
                'salary_range_end' => 55000.00,
                'deadline' => now()->addDays(45),
                'status' => 'published',
            ],
            [
                'title' => 'Product Manager',
                'description' => "We are looking for a visionary Product Manager to lead our product development lifecycle.\n\nResponsibilities:\n- Define product vision, strategy, and roadmap.\n- Work closely with engineering, design, and marketing teams.\n- Gather and analyze user feedback to iterate on features.\n\nRequirements:\n- 4+ years of product management experience.\n- Strong leadership and communication skills.\n- Technical background is a plus.",
                'location' => 'San Francisco, USA',
                'employment_type' => 'Full-time',
                'experience_level' => 'Senior',
                'salary_range_start' => 110000.00,
                'salary_range_end' => 150000.00,
                'deadline' => now()->addDays(20),
                'status' => 'published',
            ],
            [
                'title' => 'Customer Support Representative',
                'description' => "Help our customers succeed! We're hiring a Customer Support Representative to provide top-notch assistance.\n\nResponsibilities:\n- Respond to customer inquiries via email, chat, and phone.\n- Troubleshoot user issues and escalate bugs.\n- Maintain a high customer satisfaction rate.\n\nRequirements:\n- 1+ years in customer service.\n- Excellent empathy and problem-solving skills.",
                'location' => 'Remote',
                'employment_type' => 'Part-time',
                'experience_level' => 'Entry',
                'salary_range_start' => 20000.00,
                'salary_range_end' => 30000.00,
                'deadline' => null, // No deadline
                'status' => 'published',
            ],
            [
                'title' => 'System Administrator',
                'description' => "We need a reliable System Administrator to maintain our internal infrastructure.\n\nResponsibilities:\n- Manage servers, networks, and internal systems.\n- Ensure data security and regular backups.\n- Provide IT support to team members.\n\nRequirements:\n- Strong knowledge of Linux and Windows Server environments.\n- Experience with cloud providers (AWS/Azure).",
                'location' => 'Berlin, Germany',
                'employment_type' => 'Full-time',
                'experience_level' => 'Mid',
                'salary_range_start' => 70000.00,
                'salary_range_end' => 90000.00,
                'deadline' => now()->addDays(5),
                'status' => 'draft', // Draft status so it doesn't show up in career page initially
            ]
        ];

        foreach ($jobs as $job) {
            $job['slug'] = Str::slug($job['title']);
            Recruitment::create($job);
        }
    }
}
