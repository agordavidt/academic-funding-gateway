<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Payment;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Create test users for different stages
        
        // 1. User with incomplete profile
        $user1 = User::create([
            'phone_number' => '+2348123456789',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);
        
        // 2. User with completed profile but no payment
        $user2 = User::create([
            'phone_number' => '+2348123456790',
            'email' => 'jane@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '123 Test Street, Lagos',
            'school' => 'University of Lagos',
            'matriculation_number' => 'UL/2023/001',
            'state_of_origin' => 'Lagos',
            'lga' => 'Lagos Island',
            'date_of_birth' => '2000-01-15',
            'gender' => 'female',
            'bank_name' => 'GTBank',
            'account_number' => '1234567890',
            'account_name' => 'Jane Smith',
            'profile_completion_status' => 'completed',
            'profile_completed_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);
        
        // 3. User with payment but no application
        $user3 = User::create([
            'phone_number' => '+2348123456791',
            'email' => 'mike@example.com',
            'first_name' => 'Mike',
            'last_name' => 'Johnson',
            'address' => '456 Test Avenue, Abuja',
            'school' => 'University of Abuja',
            'matriculation_number' => 'UA/2023/002',
            'state_of_origin' => 'FCT',
            'lga' => 'Abuja Municipal',
            'date_of_birth' => '1999-05-20',
            'gender' => 'male',
            'bank_name' => 'First Bank',
            'account_number' => '2345678901',
            'account_name' => 'Mike Johnson',
            'profile_completion_status' => 'completed',
            'payment_status' => 'paid',
            'profile_completed_at' => now(),
            'payment_completed_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);
        
        // Create successful payment for user3
        Payment::create([
            'user_id' => $user3->id,
            'transaction_id' => 'TEST_TXN_' . time(),
            'flutterwave_ref' => 'TEST_REF_' . time(),
            'amount' => config('funding.acceptance_fee'),
            'currency' => 'NGN',
            'status' => 'success',
            'payment_method' => 'card',
            'paid_at' => now()
        ]);
        
        // 4. User with complete application
        $user4 = User::create([
            'phone_number' => '+2348123456792',
            'email' => 'sarah@example.com',
            'first_name' => 'Sarah',
            'last_name' => 'Williams',
            'address' => '789 Test Road, Kano',
            'school' => 'Ahmadu Bello University',
            'matriculation_number' => 'ABU/2023/003',
            'state_of_origin' => 'Kano',
            'lga' => 'Kano Municipal',
            'date_of_birth' => '1998-12-10',
            'gender' => 'female',
            'bank_name' => 'UBA',
            'account_number' => '3456789012',
            'account_name' => 'Sarah Williams',
            'profile_completion_status' => 'completed',
            'payment_status' => 'paid',
            'application_status' => 'pending',
            'profile_completed_at' => now(),
            'payment_completed_at' => now(),
            'application_submitted_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);
        
        // Create payment for user4
        Payment::create([
            'user_id' => $user4->id,
            'transaction_id' => 'TEST_TXN_' . (time() + 1),
            'flutterwave_ref' => 'TEST_REF_' . (time() + 1),
            'amount' => config('funding.acceptance_fee'),
            'currency' => 'NGN',
            'status' => 'success',
            'payment_method' => 'bank_transfer',
            'paid_at' => now()
        ]);
        
        // Create application for user4
        Application::create([
            'user_id' => $user4->id,
            'training_institution_id' => 1,
            'need_assessment_text' => 'I am from a low-income family and require financial assistance to complete my education. My father is a farmer and my mother is a petty trader. The financial burden of education has been overwhelming for my family, and this grant would significantly help me achieve my academic goals and contribute meaningfully to society.',
            'terms_agreed_at' => now()
        ]);
    }
}