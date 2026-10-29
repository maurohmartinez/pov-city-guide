<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

        $name = fake()->firstName() . ' ' . fake()->lastName();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->words(3, true),
            'image' => 'sample.jpg',
        ];
    }
}
