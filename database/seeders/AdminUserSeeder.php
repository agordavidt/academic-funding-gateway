<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@academicfunding.com'],
            [
                'phone_number' => '+2348000000000',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'password' => Hash::make('Admin@123'),
                'is_admin' => true,
                'profile_completion_status' => 'completed',
                'profile_completed_at' => now(),
                'email_verified_at' => now(),
            ]
        );
        
        // Create additional admin if needed
        User::updateOrCreate(
            ['email' => 'reviewer@academicfunding.com'],
            [
                'phone_number' => '+2348000000001',
                'first_name' => 'Application',
                'last_name' => 'Reviewer',
                'password' => Hash::make('Reviewer@123'),
                'is_admin' => true,
                'profile_completion_status' => 'completed',
                'profile_completed_at' => now(),
                'email_verified_at' => now(),
            ]
        );
    }
}