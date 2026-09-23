<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class TagFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst(fake()->words(2, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
