<?php

namespace Database\Factories;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'type' => 'internal_link',
            'link' => '/' . fake()->slug(),
            'page_id' => null,
            'parent_id' => null,
        ];
    }
}
