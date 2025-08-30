<?php

namespace Database\Seeders;

use App\Models\TrainingInstitution;
use Illuminate\Database\Seeder;

class TrainingInstitutionSeeder extends Seeder
{
    public function run()
    {
        $institutions = [
            [
                'name' => 'TechHub Academy',
                'description' => 'Leading technology training institute specializing in software development and digital skills.',
                'contact_email' => 'admin@techhub.academy',
            ],
            [
                'name' => 'Business Leadership Institute',
                'description' => 'Professional development programs for business management and entrepreneurship.',
                'contact_email' => 'info@businessleadership.edu.ng',
            ],
            [
                'name' => 'Digital Marketing Academy',
                'description' => 'Comprehensive digital marketing and e-commerce training programs.',
                'contact_email' => 'contact@digitalmarketing.academy',
            ],
            [
                'name' => 'Healthcare Training Center',
                'description' => 'Specialized training programs for healthcare professionals and medical assistants.',
                'contact_email' => 'training@healthcarecenter.ng',
            ],
            [
                'name' => 'Agricultural Innovation Hub',
                'description' => 'Modern agricultural techniques and agribusiness training programs.',
                'contact_email' => 'info@agrihub.ng',
            ],
        ];

        foreach ($institutions as $institution) {
            TrainingInstitution::create($institution);
        }
    }
}
