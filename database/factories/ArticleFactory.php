<?php

namespace Database\Factories;

use App\Enums\VisibilityEnum;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    use \Database\Factories\Concerns\GeneratesRandomColoredImage;

    public function configure(): static
    {
        return $this->afterCreating(function (Article $article) {
            $article->categories()->attach(Category::query()->inRandomOrder()->limit(3)->get());
            $article->tags()->attach(Tag::query()->inRandomOrder()->limit(3)->get());
        });
    }

    public function definition(): array
    {
        $title = strtoupper(fake()->words(rand(1, 5), true));

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(2),
            'image' => $this->generateRandomColoredImage(2400, 800, $title, storage_path('app/public/articles'), 'article'),
            'visibility' => fake()->randomElement(VisibilityEnum::options()),
        ];
    }
}
