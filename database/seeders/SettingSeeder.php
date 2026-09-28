<?php

namespace Database\Seeders;

use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()
            ->where('key', 'social_media_links')
            ->update(
                ['value' => json_encode([
                    ['type' => 'facebook', 'link' => fake()->url()],
                    ['type' => 'instagram', 'link' => fake()->url()],
                    ['type' => 'tiktok', 'link' => fake()->url()],
                ])]);

        Setting::query()
            ->where('key', 'homepage_sections')
            ->update(
                ['value' => json_encode([
                    ['category_id' => '1', 'layout_type' => 'cards-sm-carousel'],
                    ['category_id' => '2', 'layout_type' => 'cards-md-carousel'],
                    ['category_id' => '3', 'layout_type' => 'cards-lg'],
                ])]);
    }
}
