<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'User Surname',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        User::factory()->count(50)->create();
    }
}
