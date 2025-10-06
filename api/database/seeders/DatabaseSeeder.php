<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Truong Admin',
            'username' => 'admin',
            'email' => 'truongnvgch211359@fpt.edu.vn',
            'password' => '12345678',
            'phone' => '0987654321',
            'address' => '123 Admin Street, District 1, HCMC',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'role' => 'admin',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);
    }
}
