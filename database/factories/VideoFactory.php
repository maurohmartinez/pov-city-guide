<?php

namespace Database\Factories;

use App\Enums\VisibilityEnum;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    use \App\Traits\HasImageFactory;
    use \App\Traits\HasImageSample;

    public function configure(): static
    {
        return $this->afterCreating(function (Video $video) {
            $video->categories()->attach(Category::query()->inRandomOrder()->limit(3)->get());
            $video->tags()->attach(Tag::query()->inRandomOrder()->limit(3)->get());
        });
    }

    public function definition(): array
    {
        $this->addSampleImage('videos');

        return [
            'title' => fake()->word,
            'description' => fake()->words(10, true),
            'slug' => fake()->slug(),
            'data' => [
                'id' => '9-ochJEQpb0',
                'title' => 'Video sample',
                'image' => 'https://img.youtube.com/vi/9-ochJEQpb0/hqdefault.jpg',
                'url' => 'https://www.youtube.com/watch?v=9-ochJEQpb0',
                'provider' => 'youtube',
            ],
            'image' => 'sample.jpg',
            'visibility' => fake()->randomElement(VisibilityEnum::options()),
        ];
    }
}
