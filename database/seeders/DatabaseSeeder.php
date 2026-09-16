<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Define social media links, for the footer
        Setting::query()
            ->where('key', 'social_media_links')
            ->update(['value' => json_encode([
                ['type' => 'instagram', 'link' => fake()->url()],
                ['type' => 'facebook', 'link' => fake()->url()],
                ['type' => 'x', 'link' => fake()->url()],
                ['type' => 'tiktok', 'link' => fake()->url()],
                ['type' => 'youtube', 'link' => fake()->url()],
                ['type' => 'vimeo', 'link' => fake()->url()],
            ])]);
    }
}
