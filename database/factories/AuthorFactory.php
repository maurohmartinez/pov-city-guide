<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Cviebrock\EloquentSluggable\Services\SlugService;

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
            'slug' => SlugService::createSlug(Author::class, 'slug', $name, ['unique' => false]),
            'description' => fake()->words(3, true),
            'image' => 'sample.jpg',
        ];
    }
}
