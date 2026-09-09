<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

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
