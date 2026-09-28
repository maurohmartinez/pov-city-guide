<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()->count(7)->sequence(
            ['name' => 'Concerts'],
            ['name' => 'Fun'],
            ['name' => 'Theatre'],
            ['name' => 'Music'],
            ['name' => 'Dance'],
            ['name' => 'Film'],
            ['name' => 'Other'],
        )->create();
    }
}
