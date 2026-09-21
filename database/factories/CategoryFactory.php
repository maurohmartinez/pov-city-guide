<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    use \Database\Factories\Concerns\GeneratesRandomColoredImage;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => fake()->name,
            'slug' => Str::slug($name),
            'image' => $this->generateRandomColoredImage(2400, 800, $name, storage_path('app/public/categories'), 'category'),
        ];
    }
}
