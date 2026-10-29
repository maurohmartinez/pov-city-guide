<?php

namespace Tests\Browser\Storefront\Pages;

use App\Enums\CategoryTemplateEnum;
use App\Enums\VisibilityEnum;
use App\Models\Category;
use App\Models\Video;
use Laravel\Dusk\Browser;
use Tests\Browser\Utils\Page;

class CategoryPage extends Page
{
    use \Tests\Browser\Utils\ChecksForConsoleErrors;

    public function __construct(private readonly Category $category)
    {
    }

    /**
     * The "simple" template: a plain category landing page.
     */
    public static function simple(): self
    {
        return new self(Category::factory()->create([
            'template' => CategoryTemplateEnum::SIMPLE,
        ]));
    }

    /**
     * The "with_subcategories" template needs real child categories referenced in
     * its extras, otherwise HomeController falls back to the simple template.
     */
    public static function withSubcategories(): self
    {
        $category = Category::factory()->create([
            'template' => CategoryTemplateEnum::WITH_SUBCATEGORIES,
        ]);

        $children = Category::factory()->count(2)->create(['parent_id' => $category->id]);

        $category->update([
            'extras' => [
                'subcategories' => $children->map(fn (Category $child) => ['category_id' => $child->id])->all(),
            ],
        ]);

        return new self($category);
    }

    /**
     * The "with_videos" template: a category showing a public video.
     */
    public static function withVideos(): self
    {
        $category = Category::factory()->create([
            'template' => CategoryTemplateEnum::WITH_VIDEOS,
        ]);

        $video = Video::withoutEvents(fn (): Video => Video::factory()->create([
            'visibility' => VisibilityEnum::PUBLIC,
        ]));
        $video->categories()->attach($category->id);

        return new self($category);
    }

    public function url(): string
    {
        return '/category/' . $this->category->slug;
    }

    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee($this->category->name)
            ->assertVisible('@top')
            ->assertVisible('@related');

        $this->assertNoConsoleErrors($browser);
    }

    public function elements(): array
    {
        return [
            '@top' => '[dusk="top"]',
            '@related' => '[dusk="related"]',
        ];
    }
}
