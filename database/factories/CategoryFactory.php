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
    use \App\Traits\HasImageFactory;

    public function definition(): array
    {
        $name = fake()->words(1, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => $this->generateRandomColoredImage(2400, 800, $name, storage_path('app/public/categories'), 'category'),
        ];
    }
}
