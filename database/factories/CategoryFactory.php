<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    use \Database\Factories\Concerns\GeneratesRandomColoredImage;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Concerts', 'Fun', 'Theatre', 'Music', 'Dance', 'Film', 'Other']),
        ];
    }
}
