<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'phone_number' => $this->faker->unique()->numerify('081########'),
            'email' => $this->faker->unique()->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'school' => $this->faker->randomElement([
                'University of Lagos',
                'University of Abuja',
                'University of Port Harcourt',
                'Ahmadu Bello University',
                'University of Nigeria Nsukka',
                'Obafemi Awolowo University',
                'University of Ibadan',
            ]),
            'matriculation_number' => strtoupper($this->faker->bothify('???/####/###')),
            'registration_stage' => $this->faker->randomElement(['imported', 'profile_completion', 'payment', 'completed']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid']),
            'application_status' => $this->faker->randomElement(['pending', 'reviewing', 'accepted', 'rejected']),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
        ];
    }
}