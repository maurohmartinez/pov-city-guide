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
    use \App\Traits\HasImageFactory;
    use \App\Traits\HasImageSample;

    public function configure(): static
    {
        return $this->afterCreating(function (Article $article) {
            $article->categories()->attach(Category::query()->inRandomOrder()->limit(3)->get());
            $article->tags()->attach(Tag::query()->inRandomOrder()->limit(3)->get());
        });
    }

    public function definition(): array
    {
        $this->addSampleImage('articles');

        $title = ucfirst(fake()->words(rand(1, 5), true));

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->content(),
            'description' => fake()->words(100, true),
            'image' => 'sample.jpg',
            'visibility' => fake()->randomElement(VisibilityEnum::options()),
        ];
    }

    private function content(): array
    {
        return [
            [
                'type' => 'text',
                'size' => '12',
                'value' => implode('<br>', fake()->paragraphs(rand(1, 2))),
            ],
            [
                'type' => 'text',
                'size' => '9',
                'value' => implode('<br>', fake()->paragraphs(6)),
            ],
            [
                'type' => 'video',
                'size' => '3',
                'value' => [
                    'provider' => 'youtube',
                    'id' => 'DZ2sOfI-RuA',
                    'title' => null,
                    'image' => 'https://img.youtube.com/vi/DZ2sOfI-RuA/hqdefault.jpg',
                    'url' => 'https://www.youtube.com/watch?v=DZ2sOfI-RuA'
                ],
            ],
            [
                'type' => 'text',
                'size' => '12',
                'value' => implode('<br>', fake()->paragraphs(rand(2, 3))),
            ],
            [
                'type' => 'images',
                'size' => '12',
                'value' => [
                    'sample.jpg',
                    'sample.jpg',
                    'sample.jpg',
                    'sample.jpg',
                    'sample.jpg',
                    'sample.jpg',
                ]
            ],
            [
                'type' => 'text',
                'size' => '12',
                'value' => implode('<br>', fake()->paragraphs(rand(2, 4))),
            ],
        ];
    }
}
