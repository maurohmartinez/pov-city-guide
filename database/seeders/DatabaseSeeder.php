<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            DefaultCategorySeeder::class,
            TagSeeder::class,
            AuthorSeeder::class,
            ArticleSeeder::class,
            VideoSeeder::class,
            SettingSeeder::class,
        ]);

        Artisan::call('optimize:clear');
    }
}
