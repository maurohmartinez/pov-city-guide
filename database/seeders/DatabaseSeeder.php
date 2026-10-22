<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DefaultCategorySeeder::class,
            TagSeeder::class,
            AuthorSeeder::class,
            ArticleSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
