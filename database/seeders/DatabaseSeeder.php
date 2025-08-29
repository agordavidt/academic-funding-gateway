<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            TrainingInstitutionSeeder::class,
            // TestUserSeeder::class, // Uncomment for development
        ]);
    }
}