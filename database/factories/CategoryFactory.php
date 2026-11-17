<?php

namespace Database\Factories;

use App\Enums\CategoryTemplateEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Cviebrock\EloquentSluggable\Services\SlugService;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(1, true);

        return [
            'name' => $name,
            'template' => fake()->randomElement(CategoryTemplateEnum::options()),
            'slug' => SlugService::createSlug(Category::class, 'slug', $name, ['unique' => false]),
        ];
    }
}
