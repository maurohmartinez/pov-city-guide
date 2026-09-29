<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DefaultCategorySeeder extends Seeder
{
    use \Illuminate\Database\Console\Seeds\WithoutModelEvents;

    public function run(): void
    {
        Category::factory()->create([
            'name' => 'News',
            'slug' => 'news',
            'lft' => 2,
            'rgt' => 3,
            'depth' => 1,
            'extras' => ['showInMenu' => true],
        ]);

        Category::factory()->create([
            'name' => 'Things to do',
            'slug' => 'things-to-do',
            'lft' => 4,
            'rgt' => 5,
            'depth' => 1,
            'extras' => ['showInMenu' => true],
        ]);

        Category::factory()->create([
            'name' => 'Festivals',
            'slug' => 'festivals',
            'lft' => 5,
            'rgt' => 7,
            'depth' => 1,
            'extras' => ['showInMenu' => true],
        ]);

        Category::factory()->create([
            'name' => 'Food & Drinks',
            'slug' => 'food-and-drinks',
            'lft' => 8,
            'rgt' => 9,
            'depth' => 1,
            'extras' => ['showInMenu' => true],
        ]);

        Category::factory()->create([
            'name' => 'Cinema',
            'slug' => 'cinema',
            'lft' => 10,
            'rgt' => 11,
            'depth' => 1,
            'extras' => ['showInMenu' => true],
        ]);

        Category::factory()->create([
            'name' => 'Theatre',
            'slug' => 'theatre',
            'lft' => 12,
            'rgt' => 13,
            'depth' => 1,
            'extras' => ['showInMenu' => true],
        ]);
    }
}
