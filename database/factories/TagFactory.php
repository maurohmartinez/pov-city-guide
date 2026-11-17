<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Cviebrock\EloquentSluggable\Services\SlugService;

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
            'slug' => SlugService::createSlug(Tag::class, 'slug', $name, ['unique' => false]),
        ];
    }
}
