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
    use \App\Traits\HasImageFactory;
    use \App\Traits\HasImageSample;

    public function definition(): array
    {
        $this->addSampleImage('categories');

        $name = fake()->words(1, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => 'sample.jpg',
        ];
    }
}
