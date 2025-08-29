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
                'name' => 'Federal University of Technology, Minna',
                'description' => 'Leading technology university offering various engineering and technology programs.',
                'contact_email' => 'info@futminna.edu.ng',
                'contact_phone' => '+2349012345678',
                'address' => 'PMB 65, Minna, Niger State, Nigeria',
                'website' => 'https://futminna.edu.ng',
                'programs_offered' => [
                    'Computer Science',
                    'Electrical Engineering', 
                    'Mechanical Engineering',
                    'Civil Engineering',
                    'Information Technology'
                ],
                'max_grant_amount' => 500000.00
            ],
            [
                'name' => 'University of Abuja',
                'description' => 'Premier federal university in the nation\'s capital.',
                'contact_email' => 'info@uniabuja.edu.ng',
                'contact_phone' => '+2349012345679',
                'address' => 'PMB 117, Abuja, FCT, Nigeria',
                'website' => 'https://uniabuja.edu.ng',
                'programs_offered' => [
                    'Medicine',
                    'Law',
                    'Business Administration',
                    'Mass Communication',
                    'Political Science'
                ],
                'max_grant_amount' => 500000.00
            ],
            [
                'name' => 'Ahmadu Bello University',
                'description' => 'One of Nigeria\'s largest and oldest universities.',
                'contact_email' => 'info@abu.edu.ng',
                'contact_phone' => '+2349012345680',
                'address' => 'PMB 1044, Zaria, Kaduna State, Nigeria',
                'website' => 'https://abu.edu.ng',
                'programs_offered' => [
                    'Agriculture',
                    'Veterinary Medicine',
                    'Pharmacy',
                    'Economics',
                    'Architecture'
                ],
                'max_grant_amount' => 500000.00
            ],
            [
                'name' => 'University of Lagos',
                'description' => 'Leading university in Nigeria with excellent academic programs.',
                'contact_email' => 'info@unilag.edu.ng',
                'contact_phone' => '+2349012345681',
                'address' => 'Akoka, Lagos State, Nigeria',
                'website' => 'https://unilag.edu.ng',
                'programs_offered' => [
                    'Engineering',
                    'Medicine',
                    'Business',
                    'Creative Arts',
                    'Social Sciences'
                ],
                'max_grant_amount' => 500000.00
            ],
            [
                'name' => 'Covenant University',
                'description' => 'Private university known for academic excellence and innovation.',
                'contact_email' => 'info@covenantuniversity.edu.ng',
                'contact_phone' => '+2349012345682',
                'address' => 'KM 10, Idiroko Road, Canaan Land, Ota, Ogun State',
                'website' => 'https://covenantuniversity.edu.ng',
                'programs_offered' => [
                    'Computer Science',
                    'Business',
                    'Engineering',
                    'Mass Communication',
                    'Psychology'
                ],
                'max_grant_amount' => 500000.00
            ]
        ];
        
        foreach ($institutions as $institution) {
            TrainingInstitution::updateOrCreate(
                ['name' => $institution['name']],
                $institution
            );
        }
    }
}
