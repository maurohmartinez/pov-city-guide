<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    use \App\Traits\HasImageFactory;
    use \App\Traits\HasImageSample;

    public function definition(): array
    {
        $this->addSampleImage('authors');

        return [
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'slug' => fake()->slug(),
            'description' => fake()->words(3, true),
            'image' => 'sample.jpg',
        ];
    }
}
