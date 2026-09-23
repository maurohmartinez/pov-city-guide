<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        Category::factory()->count(7)->sequence(
            ['name' => 'Concerts'],
            ['name' => 'Fun'],
            ['name' => 'Theatre'],
            ['name' => 'Music'],
            ['name' => 'Dance'],
            ['name' => 'Film'],
            ['name' => 'Other'],
        )->create();

        Tag::factory()->count(15)->create();

        Article::factory()->count(50)->create();

        Setting::query()
            ->where('key', 'social_media_links')
            ->update(['value' => json_encode([
                ['type' => 'facebook', 'link' => fake()->url()],
                ['type' => 'instagram', 'link' => fake()->url()],
//                ['type' => 'x', 'link' => fake()->url()],
                ['type' => 'tiktok', 'link' => fake()->url()],
//                ['type' => 'youtube', 'link' => fake()->url()],
//                ['type' => 'vimeo', 'link' => fake()->url()],
            ])]);

        Setting::query()
            ->where('key', 'homepage_sections')
            ->update(['value' => json_encode([
                ['category_id' => '1', 'layout_type' => 'cards-sm-carousel'],
                ['category_id' => '2', 'layout_type' => 'cards-md-carousel'],
                ['category_id' => '3', 'layout_type' => 'cards-lg'],
            ])]);
    }
}
