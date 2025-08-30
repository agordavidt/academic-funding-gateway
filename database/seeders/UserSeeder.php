<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'phone_number' => '08123456789',
                'email' => 'john.doe@email.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Main Street, Lagos, Nigeria',
                'school' => 'University of Lagos',
                'matriculation_number' => 'UNILAG/2023/001',
                'registration_stage' => 'completed',
                'payment_status' => 'paid',
                'application_status' => 'pending',
            ],
            [
                'phone_number' => '08134567890',
                'email' => 'jane.smith@email.com',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'address' => '456 Oak Avenue, Abuja, Nigeria',
                'school' => 'University of Abuja',
                'matriculation_number' => 'UNIABUJA/2023/002',
                'registration_stage' => 'completed',
                'payment_status' => 'paid',
                'application_status' => 'accepted',
            ],
            [
                'phone_number' => '08145678901',
                'email' => 'mike.johnson@email.com',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'address' => '789 Elm Street, Port Harcourt, Nigeria',
                'school' => 'University of Port Harcourt',
                'matriculation_number' => 'UNIPORT/2023/003',
                'registration_stage' => 'completed',
                'payment_status' => 'paid',
                'application_status' => 'reviewing',
            ],
            [
                'phone_number' => '08156789012',
                'email' => null,
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'address' => null,
                'school' => 'Ahmadu Bello University',
                'matriculation_number' => 'ABU/2023/004',
                'registration_stage' => 'imported',
                'payment_status' => 'pending',
                'application_status' => 'pending',
            ],
            [
                'phone_number' => '08167890123',
                'email' => 'david.brown@email.com',
                'first_name' => 'David',
                'last_name' => 'Brown',
                'address' => '321 Pine Road, Kano, Nigeria',
                'school' => 'Bayero University Kano',
                'matriculation_number' => 'BUK/2023/005',
                'registration_stage' => 'profile_completion',
                'payment_status' => 'pending',
                'application_status' => 'pending',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create(array_merge($userData, [
                'password' => Hash::make('password123'),
            ]));

            // Create applications for completed users
            if ($user->registration_stage === 'completed') {
                Application::create([
                    'user_id' => $user->id,
                    'need_assessment_text' => $this->generateNeedAssessment($user->first_name),
                    'terms_agreed_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }

    private function generateNeedAssessment($firstName): string
    {
        $assessments = [
            "I am a dedicated student seeking to enhance my technical skills to improve my career prospects. This grant would enable me to access quality training programs that would otherwise be financially challenging for me.",
            "As a recent graduate, I believe this training opportunity will provide me with practical skills needed in today's competitive job market. The grant would be instrumental in achieving my professional development goals.",
            "I come from a humble background and this grant represents a significant opportunity to advance my education and career. I am committed to making the most of this opportunity and contributing positively to society.",
            "This grant would help me transition into the technology sector, which aligns with my passion and career aspirations. I am eager to acquire new skills that will make me more competitive in the job market.",
            "I am passionate about entrepreneurship and believe the training programs available through this grant will equip me with the necessary skills to start my own business and create employment opportunities for others.",
        ];

        return $assessments[array_rand($assessments)];
    }
}