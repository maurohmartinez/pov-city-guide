<?php

namespace Database\Factories;

use App\Enums\CategoryTemplateEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'template' => fake()->randomElement(CategoryTemplateEnum::cases()),
            'slug' => Str::slug($name),
        ];
    }
}
