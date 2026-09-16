<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'template' => 'document',
            'name' => fake()->sentence(3),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'content' => '<p>' . fake()->paragraph() . '</p>',
            'extras' => [],
            'extras_translatable' => null,
        ];
    }
}
