<?php

namespace Database\Factories;

use App\Enums\VisibilityEnum;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'content' => fake()->paragraphs(2),
            'images' => [],
            'visibility' => fake()->randomElement(VisibilityEnum::options()),
        ];
    }
}
